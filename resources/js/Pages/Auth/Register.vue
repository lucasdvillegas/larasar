<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import { Head, Link, router } from '@inertiajs/vue3'
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

const [name, nameProps] = defineField('name', fieldConfig)
const [email, emailProps] = defineField('email', fieldConfig)
const [password, passwordProps] = defineField('password', fieldConfig)
const [password_confirmation, passwordConfirmationProps] = defineField('password_confirmation', fieldConfig)

const onSubmit = handleSubmit((values) => {
  saving.value = true

  router.post(route('register'), values, {
    onFinish: () => {
      saving.value = false
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

    <q-form class="q-gutter-y-sm" @submit="onSubmit">
      <q-input
        v-model="name"
        outlined
        dense
        type="text"
        label="Nombre"
        v-bind="nameProps"
        :class="errors.name ? 'q-mb-md' : 'q-mb-sm'"
      />

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
        label="Contraseña"
        v-bind="passwordProps"
        autocomplete="new-password"
        :class="errors.password ? 'q-mb-md' : 'q-mb-sm'"
      />

      <q-input
        v-model="password_confirmation"
        outlined
        dense
        type="password"
        label="Confirmar Contraseña"
        v-bind="passwordConfirmationProps"
        autocomplete="new-password"
        :class="errors.password_confirmation ? 'q-mb-md' : 'q-mb-sm'"
      />

      <div class="flex items-center justify-between q-mt-md">
        <Link
          :href="route('login')"
          class="text-caption text-grey-8 hover-underline"
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