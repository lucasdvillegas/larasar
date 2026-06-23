<script setup>
import { ref, nextTick } from 'vue'
import { useForm as useInertiaForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const $q = useQuasar()
const saving = ref(false)
const confirmingUserDeletion = ref(false)
const passwordInputRef = ref(null)

const schema = yup.object({
  password: yup.string().required('Para proceder debes ingresar tu contraseña para confirmar.').label('Contraseña'),
})

const initialValues = ref({
  password: '',
})

const { defineField, handleSubmit, errors, resetForm } = useForm({
  validationSchema: schema,
  initialValues: initialValues,
})

const fieldConfig = (state) => ({
  props: {
    error: !!state.errors[0],
    'error-message': state.errors[0],
  },
})

const [password, passwordProps] = defineField('password', fieldConfig)

const inertiaForm = useInertiaForm({ password: '' })

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true
  nextTick(() => {
    setTimeout(() => passwordInputRef.value?.focus(), 150)
  })
}

const deleteUser = handleSubmit((values, actions) => {
  saving.value = true
  inertiaForm.password = values.password

  inertiaForm.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: (backendErrors) => {
      if (backendErrors) {
        actions.setErrors(backendErrors)
      }

      $q.notify({
        type: 'negative',
        position: 'top',
        message: 'La contraseña ingresada es incorrecta.',
      })

      passwordInputRef.value?.focus()
    },
    onFinish: () => {
      saving.value = false
      inertiaForm.reset('password')
    },
  })
}, () => {
  $q.notify({
    type: 'negative',
    position: 'top',
    message: 'Por favor revise los errores en el formulario',
  })
})

const closeModal = () => {
  confirmingUserDeletion.value = false
  resetForm()
  inertiaForm.reset('password')
}
</script>

<template>
  <q-card-section class="q-pb-none">
    <div class="text-h6">
      Eliminar Cuenta
    </div>
    <p class="text-body2 text-grey-9">
      Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán permanentemente. Antes de proceder, descarga cualquier información que desees conservar.
    </p>
  </q-card-section>

  <q-card-section>
    <q-btn
      color="negative"
      label="Eliminar Cuenta"
      unelevated
      @click="confirmUserDeletion"
    />

    <q-dialog v-model="confirmingUserDeletion" persistent>
      <q-card style="min-width: 350px; max-width: 500px;">
        <q-card-section>
          <div class="text-h6 text-grey-9">
            ¿Estás seguro de que deseas eliminar tu cuenta?
          </div>
          <div class="text-body2 text-grey-9 q-mt-sm">
            Una vez eliminada la cuenta, todos sus datos asociados se perderán para siempre. Por favor, introduce tu contraseña para confirmar que eres el propietario legítimo.
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit.prevent="deleteUser">
            <q-input
              ref="passwordInputRef"
              v-model="password"
              outlined
              dense
              type="password"
              label="Contraseña"
              v-bind="passwordProps"
              autocomplete="current-password"
              :class="errors.password ? 'q-mb-md' : 'q-mb-none'"
              @keyup.enter="deleteUser"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="q-pa-md">
          <q-btn
            flat
            label="Cancelar"
            color="grey-9"
            :disabled="saving"
            @click="closeModal"
          />
          <q-btn
            unelevated
            label="Eliminar Definitivamente"
            color="negative"
            :loading="saving"
            :disabled="saving"
            @click="deleteUser"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-card-section>
</template>