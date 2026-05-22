<script setup>
import { ref, nextTick } from 'vue'
import { useForm as useVeeForm } from 'vee-validate'
import { useForm as useInertiaForm } from '@inertiajs/vue3'

import * as yup from 'yup'
import es from 'yup-es'

yup.setLocale(es)

const saving = ref(false)
const confirmingUserDeletion = ref(false)
const passwordInputRef = ref(null)

const schema = yup.object({
    password: yup.string().required('Para proceder debes ingresar tu contraseña para confirmar.').label('Contraseña')
})

const { defineField, handleSubmit, errors: veeErrors, resetForm } = useVeeForm({
    validationSchema: schema,
    initialValues: { password: '' }
})

const fieldConfig = (state) => ({
    props: {
        error: !!state.errors[0],
        'error-message': state.errors[0],
    },
})

const [password, passwordProps] = defineField('password', fieldConfig)

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true
    nextTick(() => {
        setTimeout(() => passwordInputRef.value?.focus(), 150)
    })
}

const deleteUser = handleSubmit((values) => {
    saving.value = true

    const inertiaForm = useInertiaForm(values)

    inertiaForm.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {
            passwordInputRef.value?.focus()
        },
        onFinish: () => {
            saving.value = false
            resetForm()
        }
    })
})

const closeModal = () => {
    confirmingUserDeletion.value = false
    resetForm()
}
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">
        Eliminar Cuenta
      </h2>
      <p class="text-sm text-gray-600">
        Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán permanentemente. Antes de proceder, descarga cualquier información que desees conservar.
      </p>
    </header>

    <q-btn
      color="negative"
      label="Eliminar Cuenta"
      unelevated
      class="q-mt-sm"
      @click="confirmUserDeletion"
    />

    <q-dialog v-model="confirmingUserDeletion" persistent>
      <q-card style="min-width: 350px; max-width: 500px;" class="q-pa-md">
        <q-card-section>
          <div class="text-h6 text-grey-9">
            ¿Estás seguro de que deseas eliminar tu cuenta?
          </div>
          <div class="text-body2 text-grey-7 q-mt-sm">
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
              :class="veeErrors.password ? 'q-mb-md' : 'q-mb-none'"
              @keyup.enter="deleteUser"
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="text-primary q-mt-sm">
          <q-btn 
            flat 
            label="Cancelar" 
            color="grey-7" 
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
  </section>
</template>