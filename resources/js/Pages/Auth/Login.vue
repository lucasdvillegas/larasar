<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import { Head, Link, router } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
  email: yup.string().email().required().label('Correo electrónico'),
  password: yup.string().required().label('Contraseña'),
  remember: yup.boolean().default(false).label('Recuérdame')
})

const initialValues = ref({
  email: '',
  password: '',
  remember: false
})

const { defineField, handleSubmit, errors: veeErrors } = useForm({
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
const [remember, rememberProps] = defineField('remember', fieldConfig)

const onSubmit = handleSubmit((values) => {
  saving.value = true

  router.post(route('login'), values, {
    onError: (backendErrors) => {
      $q.notify({
        color: 'negative',
        position: 'top',
        message: backendErrors.email || 'Error al iniciar sesión. Por favor, verifique sus datos.',
        icon: 'mdi-alert'
      })
    },
    onFinish: () => {
      saving.value = false
    },
  })
})
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="q-mb-md text-positive text-caption">
      {{ status }}
    </div>

    <q-form class="q-gutter-y-sm" @submit="onSubmit">
      <q-input
        v-model="email"
        outlined
        dense
        type="email"
        label="Email"
        v-bind="emailProps"
        autocomplete="username"
        :class="veeErrors.email ? 'q-mb-md' : 'q-mb-sm'"
      />

      <q-input
        v-model="password"
        outlined
        dense
        type="password"
        label="Contraseña"
        v-bind="passwordProps"                
        autocomplete="current-password"
        :class="veeErrors.password ? 'q-mb-md' : 'q-mb-sm'"
      />

      <div class="flex items-center justify-between">
        <q-checkbox 
          v-model="remember" 
          name="remember" 
          v-bind="rememberProps"
          label="Recuérdame" 
          dense
          class="text-grey-8"
        />

        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="text-caption text-grey-8 hover-underline"
        >
          ¿Olvidaste tu contraseña?
        </Link>
      </div>

      <div class="flex items-center justify-end q-mt-md">
        <q-btn
          type="submit"
          color="primary"
          label="Iniciar Sesión"
          :loading="saving"
          :disabled="saving"
          unelevated
        />
      </div>
    </q-form>
  </GuestLayout>
</template>