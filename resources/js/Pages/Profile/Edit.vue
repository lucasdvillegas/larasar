<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from '../../Components/Profile/DeleteUserForm.vue';
import UpdatePasswordForm from '../../Components/Profile/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '../../Components/Profile/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
  <Head title="Perfil" />
  <AuthenticatedLayout>
    <div>
      <q-card 
        flat 
        bordered 
        class="q-pa-md bg-white q-mb-sm"
      >
        <UpdateProfileInformationForm 
          :must-verify-email="mustVerifyEmail" 
          :status="status" 
        />
      </q-card>

      <q-card 
        flat 
        bordered 
        class="q-pa-md bg-white q-mb-sm"
      >
        <UpdatePasswordForm />
      </q-card>

      <q-card 
        flat 
        bordered 
        class="q-pa-md bg-white q-mb-sm"
      >
        <DeleteUserForm />
      </q-card>
    </div>
  </AuthenticatedLayout>
</template>