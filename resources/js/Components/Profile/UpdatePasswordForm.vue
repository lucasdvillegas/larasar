<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import { router } from '@inertiajs/vue3'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const $q = useQuasar()
const saving = ref(false)

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

const { defineField, handleSubmit, errors, resetForm } = useForm({
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

const onSubmit = handleSubmit((values, actions) => {
  saving.value = true

  router.put(route('password.update'), values, {
    preserveScroll: true,
    onSuccess: () => {
      resetForm()
      $q.notify({
        type: 'positive',
        position: 'top-right',
        message: 'Contraseña actualizada con éxito.',
      })
    },
    onError: (backendErrors) => {
      if (backendErrors) {
        actions.setErrors(backendErrors)
      }

      if (backendErrors.password) {
        resetForm({ values: { ...values, password: '', password_confirmation: '' } })
        passwordRef.value?.focus()
      }

      if (backendErrors.current_password) {
        resetForm({ values: { ...values, current_password: '' } })
        currentPasswordRef.value?.focus()
      }

      $q.notify({
        type: 'negative',
        position: 'top',
        message: backendErrors.current_password || backendErrors.password || 'Error al intentar actualizar la contraseña.',
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
    <header class="q-mb-md">
      <h2 class="text-h6 text-weight-medium text-grey-9">
        Actualizar Contraseña
      </h2>
      <p class="text-body2 text-grey-8 q-mt-xs">
        Asegúrate de que tu cuenta esté utilizando una contraseña larga y aleatoria para mantener la seguridad.
      </p>
    </header>

    <q-form class="q-gutter-y-sm" @submit="onSubmit"> 
      <q-input
        ref="currentPasswordRef"
        v-model="current_password"
        outlined
        dense
        type="password"
        label="Contraseña Actual"
        v-bind="currentPasswordProps"
        autocomplete="current-password"
        :class="errors.current_password ? 'q-mb-md' : 'q-mb-none'"
      />

      <q-input
        ref="passwordRef"
        v-model="password"
        outlined
        dense
        type="password"
        label="Nueva Contraseña"
        v-bind="passwordProps"
        autocomplete="new-password"
        :class="errors.password ? 'q-mb-md' : 'q-mb-none'"
      />

      <q-input
        v-model="password_confirmation"
        outlined
        dense
        type="password"
        label="Confirmar Nueva Contraseña"
        v-bind="passwordConfirmationProps"
        autocomplete="new-password"
        :class="errors.password_confirmation ? 'q-mb-md' : 'q-mb-none'"
      />

      <div class="row items-center q-mt-sm">
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