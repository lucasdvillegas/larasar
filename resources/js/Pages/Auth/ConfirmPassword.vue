<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { Head, useForm as useInertiaForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
    password: yup.string().required().label('Contraseña')
})

const initialValues = ref({
    password: ''
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

const [password, passwordProps] = defineField('password', fieldConfig)

const onSubmit = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.post(route('password.confirm'), {
        onFinish: () => {
            saving.value = false
            inertiaForm.reset()
        },
        onError: (backendErrors) => {
            $q.notify({
                color: 'negative',
                position: 'top',
                message: backendErrors.password || 'La contraseña proporcionada es incorrecta.',
                icon: 'mdi-alert'
            })
        }
    })
})
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="q-mb-md text-body2 text-grey-8 text-justify">
            Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.
        </div>

        <q-form @submit="onSubmit" class="q-gutter-y-md">
            <q-input
                outlined
                dense
                type="password"
                label="Contraseña"
                v-model="password"
                v-bind="passwordProps"
                autocomplete="current-password"
                :class="veeErrors.password ? 'q-mb-md' : 'q-mb-sm'"
            />

            <div class="flex items-center justify-end q-mt-md">
                <q-btn
                    type="submit"
                    color="primary"
                    label="Confirmar"
                    :loading="saving"
                    :disabled="saving"
                    unelevated
                />
            </div>
        </q-form>
    </GuestLayout>
</template>

<style scoped>
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