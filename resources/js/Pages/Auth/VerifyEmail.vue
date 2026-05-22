<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const props = defineProps({
    status: {
        type: String,
    },
});

const saving = ref(false)

const inertiaForm = useInertiaForm({})

const submit = () => {
    saving.value = true
    inertiaForm.post(route('verification.send'), {
        onFinish: () => {
            saving.value = false
        }
    })
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />

    <div class="q-mb-md text-body2 text-grey-8 text-justify">
      ¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.
    </div>

    <q-banner 
      v-if="verificationLinkSent" 
      dense 
      rounded 
      class="bg-green-1 text-positive q-mb-md text-caption text-weight-medium"
    >
      <template #avatar>
        <q-icon name="mdi-checkbox-marked-circle-outline" color="positive" />
      </template>
      Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.
    </q-banner>

    <q-form @submit.prevent="submit">
      <div class="flex items-center justify-between q-mt-lg">
        <q-btn
          type="submit"
          color="primary"
          label="Reenviar correo de verificación"
          :loading="saving"
          :disabled="saving"
          unelevated
        />

        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="text-caption text-grey-7 hover-underline backend-logout-btn"
        >
          Cerrar sesión
        </Link>
      </div>
    </q-form>
  </GuestLayout>
</template>