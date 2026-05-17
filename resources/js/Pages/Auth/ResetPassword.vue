<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { Head, useForm as useInertiaForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
    token: yup.string().required(),
    email: yup.string().email().required().label('Correo electrónico'),
    password: yup.string().min(8).required().label('Contraseña'),
    password_confirmation: yup.string()
        .oneOf([yup.ref('password'), null], 'Las contraseñas deben coincidir')
        .required()
        .label('Confirmar contraseña')
})

const initialValues = ref({
    token: props.token,
    email: props.email,
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

const [email, emailProps] = defineField('email', fieldConfig)
const [password, passwordProps] = defineField('password', fieldConfig)
const [password_confirmation, passwordConfirmationProps] = defineField('password_confirmation', fieldConfig)

const onSubmit = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.post(route('password.store'), {
        onFinish: () => {
            saving.value = false
            inertiaForm.reset('password', 'password_confirmation')
        },
        onError: (backendErrors) => {
            // Muestra errores críticos de backend (ej: token expirado o inválido)
            $q.notify({
                color: 'negative',
                position: 'top',
                message: backendErrors.email || backendErrors.token || 'Error al restablecer la contraseña.',
                icon: 'mdi-alert'
            })
        }
    })
})
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <q-form @submit="onSubmit" class="q-gutter-y-md">
            
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
                label="Nueva Contraseña"
                v-model="password"
                v-bind="passwordProps"
                autocomplete="new-password"
                :class="veeErrors.password ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
                outlined
                dense
                type="password"
                label="Confirmar Nueva Contraseña"
                v-model="password_confirmation"
                v-bind="passwordConfirmationProps"
                autocomplete="new-password"
                :class="veeErrors.password_confirmation ? 'q-mb-md' : 'q-mb-sm'"
            />

            <div class="flex items-center justify-end q-mt-md">
                <q-btn
                    type="submit"
                    color="primary"
                    label="Restablecer contraseña"
                    :loading="saving"
                    :disabled="saving"
                    unelevated
                />
            </div>
        </q-form>
    </GuestLayout>
</template>

<style scoped>
/* Evita el doble marco del navegador en entornos Chromium */
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