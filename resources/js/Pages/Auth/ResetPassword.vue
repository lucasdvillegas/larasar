<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import { Head, router } from '@inertiajs/vue3'
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

const { defineField, handleSubmit, errors } = useForm({
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

  router.post(route('password.store'), values, {
    onError: (backendErrors) => {
      $q.notify({
        color: 'negative',
        position: 'top',
        message: backendErrors.email || backendErrors.token || 'Error al restablecer la contraseña.',
        icon: 'mdi-alert'
      })
    },
    onFinish: () => {
      saving.value = false
      router.visit(route('login'))
    },
  })
})
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

    <q-form class="q-gutter-y-md" @submit="onSubmit">
      <q-input
        v-model="email"
        outlined
        dense
        type="email"
        label="Email"
        v-bind="emailProps"
        autocomplete="username"
        :class="errors.email ? 'q-mb-md' : 'q-mb-sm'"
      />

      <q-input
        v-model="password"
        outlined
        dense
        type="password"
        label="Nueva Contraseña"
        v-bind="passwordProps"
        autocomplete="new-password"
        :class="errors.password ? 'q-mb-md' : 'q-mb-sm'"
      />

      <q-input
        v-model="password_confirmation"
        outlined
        dense
        type="password"
        label="Confirmar Nueva Contraseña"
        v-bind="passwordConfirmationProps"
        autocomplete="new-password"
        :class="errors.password_confirmation ? 'q-mb-md' : 'q-mb-sm'"
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