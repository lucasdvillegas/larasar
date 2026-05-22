<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useForm as useVeeForm } from 'vee-validate'
import { Head, useForm as useInertiaForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

defineProps({
  status: {
    type: String,
  },
});

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
  email: yup.string().email().required().label('Correo electrónico')
})

const initialValues = ref({
  email: ''
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

const onSubmit = handleSubmit((values) => {
  saving.value = true

  const inertiaForm = useInertiaForm(values)

  inertiaForm.post(route('password.email'), {
    onFinish: () => {
      saving.value = false
    },
    onError: (backendErrors) => {
      $q.notify({
        color: 'negative',
        position: 'top',
        message: backendErrors.email || 'Ocurrió un error al procesar la solicitud.',
        icon: 'mdi-alert'
      })
    }
  })
})
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <div class="q-mb-md text-body2 text-grey-8 text-justify">
      ¿Olvidaste tu contraseña? No hay problema. Indícanos tu dirección de correo electrónico y te enviaremos un enlace
      para restablecerla que te permitirá elegir una nueva.
    </div>

    <div v-if="status" class="q-mb-md text-positive text-caption font-medium">
      {{ status }}
    </div>

    <q-form class="q-gutter-y-sm" @submit="onSubmit">
      <q-input
        v-model="email" outlined dense type="email" label="Email" v-bind="emailProps" autocomplete="username"
        :class="veeErrors.email ? 'q-mb-md' : 'q-mb-sm'"
      />

      <div class="flex items-center justify-end q-mt-md">
        <q-btn
          type="submit" color="primary" label="Enviar enlace de restablecimiento" :loading="saving"
          :disabled="saving" unelevated
        />
      </div>
    </q-form>
  </GuestLayout>
</template>