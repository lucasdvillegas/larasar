<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { Link, useForm as useInertiaForm, usePage } from '@inertiajs/vue3'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const $q = useQuasar()
const saving = ref(false)
const user = usePage().props.auth.user

// 1. Esquema de validación
const schema = yup.object({
    name: yup.string().required().label('Nombre'),
    email: yup.string().email().required().label('Correo electrónico'),
})

// 2. Valores iniciales desde la sesión activa de Inertia
const initialValues = ref({
    name: user.name,
    email: user.email,
})

// 3. Inicialización de Vee-Validate
const { defineField, handleSubmit, errors: veeErrors } = useVeeForm({
    validationSchema: schema,
    initialValues: initialValues,
})

const fieldConfig = (state) => ({
    props: {
        error: !!state.errors[0],
        'error-message': state.errors[0],
    },
})

const [name, nameProps] = defineField('name', fieldConfig)
const [email, emailProps] = defineField('email', fieldConfig)

// 4. Envío con sincronización de estado exitoso de Inertia
const onSubmit = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.patch(route('profile.update'), {
        preserveScroll: true,
        onFinish: () => {
            saving.value = false
        },
        onSuccess: () => {
            $q.notify({
                color: 'positive',
                position: 'bottom-left',
                message: 'Información del perfil actualizada.',
                icon: 'check'
            })
        },
        onError: (backendErrors) => {
            $q.notify({
                color: 'negative',
                position: 'top',
                message: backendErrors.email || backendErrors.name || 'Error al actualizar el perfil.',
                icon: 'mdi-alert'
            })
        }
    })
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Información del Perfil
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Actualiza la información de tu cuenta y tu dirección de correo electrónico.
            </p>
        </header>

        <q-form @submit="onSubmit" class="q-mt-sm q-gutter-y-sm">
            
            <q-input
                outlined
                dense
                type="text"
                label="Nombre"
                v-model="name"
                v-bind="nameProps"
                autocomplete="name"
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Tu dirección de correo no está verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-caption text-primary hover-underline backend-btn"
                    >
                        Haz clic aquí para reenviar el correo de verificación.
                    </Link>
                </p>

                <q-banner 
                    v-show="status === 'verification-link-sent'" 
                    dense 
                    rounded 
                    class="bg-green-1 text-positive q-mt-sm text-caption"
                >
                    Un nuevo enlace de verificación ha sido enviado a tu dirección de correo electrónico.
                </q-banner>
            </div>

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
.hover-underline:hover { text-decoration: underline; }
.backend-btn { background: none; border: none; padding: 0; cursor: pointer; }

:deep(.q-field__native), 
:deep(.q-field__input), 
:deep(.q-field__control), 
:deep(.q-field__control *), 
:deep(input.q-field__native:focus) {
  outline: none !important; outline-width: 0 !important; box-shadow: none !important;
}
</style>