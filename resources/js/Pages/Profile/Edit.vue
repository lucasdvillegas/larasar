<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
//import DeleteUserForm from '../../Components/Profile/DeleteUserForm.vue';
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
    <q-page class="q-pa-md" style="padding-bottom:110px">
      <q-card flat bordered class="bg-white q-mb-md">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
        />
      </q-card>

      <q-card flat bordered class="bg-white q-mb-md">
        <UpdatePasswordForm />
      </q-card>

      <!-- <q-card flat bordered class="bg-white q-mb-md">
        <DeleteUserForm />
      </q-card> -->
    </q-page>
  </AuthenticatedLayout>
</template>