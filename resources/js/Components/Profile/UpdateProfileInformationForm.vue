<script setup>
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
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
})

const $q = useQuasar()
const page = usePage()
const saving = ref(false)
const user = page.props.auth.user

const schema = yup.object({
  name: yup.string().required().label('Nombre'),
  email: yup.string().email().required().label('Correo electrónico'),
})

const initialValues = ref({
  name: user.name,
  email: user.email,
})

const { defineField, handleSubmit, errors } = useForm({
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

const onSubmit = handleSubmit((values, actions) => {
  saving.value = true

  router.patch(route('profile.update'), values, {
    preserveScroll: true,
    onSuccess: () => {
      $q.notify({
        type: 'positive',
        position: 'top',
        message: page.props.flash.success || 'Información del perfil actualizada.',
      })
    },
    onError: (backendErrors) => {
      if (backendErrors) {
        actions.setErrors(backendErrors)
      }

      $q.notify({
        type: 'negative',
        position: 'top',
        message: page.props.flash.error || 'Los datos enviados no son válidos.',
      })
    },
    onFinish: () => {
      saving.value = false
    },
  })
}, () => {
  $q.notify({
    type: 'negative',
    position: 'top',
    message: 'Por favor revise los errores en el formulario',
  })
})
</script>

<template>
  <q-card-section class="q-pb-none">
    <div class="text-h6">Información del Perfil</div>
    <p class="text-body2 text-grey-8 q-mt-xs">
      Actualiza la información de tu cuenta y tu dirección de correo electrónico.
    </p>
  </q-card-section>

  <q-card-section>
    <q-form class="q-gutter-y-sm" @submit="onSubmit">
      <q-input
        v-model="name"
        outlined
        dense
        type="text"
        label="Nombre"
        v-bind="nameProps"
        autocomplete="name"
        :class="errors.name ? 'q-mb-md' : 'q-mb-none'"
      />

      <q-input
        v-model="email"
        outlined
        dense
        type="email"
        label="Email"
        v-bind="emailProps"
        autocomplete="username"
        :class="errors.email ? 'q-mb-md' : 'q-mb-none'"
      />

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-body2 text-grey-9 q-mt-sm">
          Tu dirección de correo no está verificada.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="text-primary cursor-pointer"
            style="background: none; border: none; padding: 0; text-decoration: underline;"
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
  </q-card-section>
</template>