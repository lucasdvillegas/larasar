<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { useForm as useInertiaForm } from '@inertiajs/vue3'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const $q = useQuasar()
const saving = ref(false)

// Referencias HTML para re-enfocar inputs si falla el backend
const currentPasswordRef = ref(null)
const passwordRef = ref(null)

const schema = yup.object({
    current_password: yup.string().required().label('Contraseña actual'),
    password: yup.string().min(8).required().label('Nueva contraseña'),
    password_confirmation: yup.string()
        .oneOf([yup.ref('password'), null], 'Las contraseñas deben coincidir')
        .required()
        .label('Confirmar nueva contraseña')
})

const { defineField, handleSubmit, errors: veeErrors, resetForm } = useVeeForm({
    validationSchema: schema,
    initialValues: { current_password: '', password: '', password_confirmation: '' }
})

const fieldConfig = (state) => ({
    props: {
        error: !!state.errors[0],
        'error-message': state.errors[0],
    },
})

const [current_password, currentPasswordProps] = defineField('current_password', fieldConfig)
const [password, passwordProps] = defineField('password', fieldConfig)
const [password_confirmation, passwordConfirmationProps] = defineField('password_confirmation', fieldConfig)

const onSubmit = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            resetForm()
            $q.notify({
                color: 'positive',
                position: 'top-right',
                message: 'Contraseña actualizada con éxito.',
                icon: 'mdi-check-circle'
            })
        },
        onError: (backendErrors) => {
            if (backendErrors.password) {
                resetForm({ values: { ...values, password: '', password_confirmation: '' } })
                passwordRef.value?.focus()
            }
            if (backendErrors.current_password) {
                resetForm({ values: { ...values, current_password: '' } })
                currentPasswordRef.value?.focus()
            }
            
            $q.notify({
                color: 'negative',
                position: 'top',
                message: backendErrors.current_password || backendErrors.password || 'Error al intentar actualizar la contraseña.',
                icon: 'mdi-alert'
            })
        },
        onFinish: () => {
            saving.value = false
        }
    })
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Actualizar Contraseña
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Asegúrate de que tu cuenta esté utilizando una contraseña larga y aleatoria para mantener la seguridad.
            </p>
        </header>

        <q-form @submit="onSubmit" class="q-mt-sm q-gutter-y-sm"> 
            <q-input
                outlined
                dense
                ref="currentPasswordRef"
                type="password"
                label="Contraseña Actual"
                v-model="current_password"
                v-bind="currentPasswordProps"
                autocomplete="current-password"
                :class="veeErrors.current_password ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
                outlined
                dense
                ref="passwordRef"
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

            <div class="flex items-center q-mt-sm">
                <q-btn
                    type="submit"
                    color="primary"
                    label="Guardar"
                    :loading="saving"
                    :disabled="saving"
                    unelevated
                />
            </div>
        </q-form>
    </section>
</template>

<style scoped>
:deep(.q-field__native), 
:deep(.q-field__input), 
:deep(.q-field__control), 
:deep(.q-field__control *), 
:deep(input.q-field__native:focus) {
  outline: none !important; outline-width: 0 !important; box-shadow: none !important;
}
</style>