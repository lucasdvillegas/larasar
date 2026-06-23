<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';

const $q = useQuasar();

const triggerDialogPlugin = () => {
  $q.dialog({
    title: 'Título del Diálogo (Plugin)',
    message: 'Este es un mensaje de prueba disparado por código utilizando el Plugin de Quasar en tu monolito.',
    cancel: {
      flat: true,
      label: 'Cancelar',
      color: 'negative'
    },
    ok: {
      flat: true,
      label: 'Aceptar',
      color: 'primary'
    },
    persistent: true
  }).onOk(() => {
    $q.notify({
      type: 'positive',
      message: 'Aceptaste la acción del diálogo',
      position: 'top-right'
    });
  }).onCancel(() => { });
};

const triggerNotification = () => {
  $q.notify({
    type: 'positive',
    message: '¡Acción realizada con éxito!',
    position: 'top-right',
    timeout: 2500
  });
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <q-page class="q-pa-md" style="padding-bottom:110px">
      <q-card class="bg-white shadow-1">
        <q-card-section class="q-pa-md text-grey-9">
          <div class="text-subtitle1 q-mb-md">
            Dashboard de Prueba
          </div>
                  
          <div class="row q-gutter-sm">
            <q-btn 
              unelevated
              color="primary" 
              label="Abrir Dialog (Plugin)" 
              @click="triggerDialogPlugin" 
            />
                      
            <q-btn 
              unelevated
              color="secondary" 
              label="Mostrar Notificación" 
              @click="triggerNotification" 
            />
          </div>
        </q-card-section>
      </q-card>
    </q-page>
  </AuthenticatedLayout>
</template>