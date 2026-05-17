<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
    name: yup.string().required().label('Nombre'),
    email: yup.string().email().required().label('Correo electrónico'),
    password: yup.string().min(8).required().label('Contraseña'),
    password_confirmation: yup.string()
        .oneOf([yup.ref('password'), null], 'Las contraseñas deben coincidir')
        .required()
        .label('Confirmar contraseña')
})

const initialValues = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const { defineField, handleSubmit, errors: veeErrors } = useVeeForm({
    validationSchema: schema,
    initialValues: initialValues,
});

const fieldConfig = (state) => ({
    props: {
        error: !!state.errors[0],
        'error-message': state.errors[0],
    },
})

const [name, nameProps] = defineField('name', fieldConfig)
const [email, emailProps] = defineField('email', fieldConfig)
const [password, passwordProps] = defineField('password', fieldConfig)
const [password_confirmation, passwordConfirmationProps] = defineField('password_confirmation', fieldConfig)

const onSubmit = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.post(route('register'), {
        onFinish: () => {
            saving.value = false
            inertiaForm.reset('password', 'password_confirmation')
        },
        onError: (backendErrors) => {
            $q.notify({
                color: 'negative',
                position: 'top',
                message: backendErrors.email || backendErrors.name || 'Error al registrar la cuenta. Por favor compruebe los datos.',
                icon: 'mdi-alert'
            })
        }
    })
})
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <q-form @submit="onSubmit" class="q-gutter-y-sm">
            
            <q-input
                outlined
                dense
                type="text"
                label="Nombre"
                v-model="name"
                v-bind="nameProps"
                :class="veeErrors.name ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
                outlined
                dense
                type="email"
                label="Email"
                v-model="email"
                v-bind="emailProps"
                autocomplete="username"
                :class="veeErrors.email ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
                outlined
                dense
                type="password"
                label="Contraseña"
                v-model="password"
                v-bind="passwordProps"
                autocomplete="new-password"
                :class="veeErrors.password ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
                outlined
                dense
                type="password"
                label="Confirmar Contraseña"
                v-model="password_confirmation"
                v-bind="passwordConfirmationProps"
                autocomplete="new-password"
                :class="veeErrors.password_confirmation ? 'q-mb-md' : 'q-mb-sm'"
            />

            <div class="flex items-center justify-between q-mt-md">
                <Link
                    :href="route('login')"
                    class="text-caption text-grey-7 hover-underline"
                >
                    ¿Ya estás registrado?
                </Link>

                <q-btn
                    type="submit"
                    color="primary"
                    label="Registrarse"
                    :loading="saving"
                    :disabled="saving"
                    unelevated
                />
            </div>
        </q-form>
    </GuestLayout>
</template>

<style scoped>
.hover-underline:hover {
    text-decoration: underline;
}

/* Forzamos al navegador a respetar estrictamente el diseño limpio de Quasar */
:deep(.q-field__native),
:deep(.q-field__input),
:deep(.q-field__control),
:deep(.q-field__control *),
:deep(input.q-field__native:focus) {
  outline: none !important;
  outline-width: 0 !important;
  box-shadow: none !important;
}
</style>