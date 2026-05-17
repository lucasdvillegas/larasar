<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const $q = useQuasar()

const clients = ref([
  { id: 1, first_name: 'Juan', last_name: 'Pérez', status: 'active' },
  { id: 2, first_name: 'María', last_name: 'Rodríguez', status: 'active' },
  { id: 3, first_name: 'Carlos', last_name: 'López', status: 'inactive' },
  { id: 4, first_name: 'Ana', last_name: 'Martínez', status: 'active' },
  { id: 5, first_name: 'Diego', last_name: 'Gómez', status: 'inactive' }
])

const columns = [
  {
    name: 'first_name',
    required: true,
    label: 'Nombre',
    align: 'left',
    field: 'first_name'
  },
  {
    name: 'last_name',
    required: true,
    label: 'Apellido',
    align: 'left',
    field: 'last_name'
  },
  {
    name: 'status',
    required: true,
    label: 'Estado',
    align: 'left',
    field: 'status'
  },
  {
    name: 'actions',
    label: '',
    align: 'right'
  }
]

function onDeleteRow(row) {
  $q.dialog({
    title: 'Confirmar eliminación',
    message: `¿Está seguro que quiere borrar al cliente ${row.first_name} ${row.last_name}?`,
    cancel: { flat: true, label: 'Cancelar', color: 'grey-7' },
    ok: { unelevated: true, label: 'Eliminar', color: 'negative' },
    persistent: true
  }).onOk(() => {
    $q.notify({
      color: 'info',
      position: 'bottom-left',
      message: `Acción simulada: Eliminando ID ${row.id}`,
      icon: 'info'
    })
  })
}
</script>

<template>
  <Head title="Clientes" />

  <AuthenticatedLayout>
    <div>
      <q-table
        flat
        bordered
        title="Listado de Clientes"
        :rows="clients"
        :columns="columns"
        row-key="id"
        class="bg-white"
        :rows-per-page-options="[0]" 
      >
        <template v-slot:body-cell-status="props">
          <q-td :props="props">
            <q-badge 
              :color="props.row.status === 'active' ? 'positive' : 'negative'"
              outline
            >
              {{ props.row.status === 'active' ? 'Activo' : 'Inactivo' }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              size="sm"
              flat
              round
              color="primary"
              icon="edit"
              title="Editar"
              @click="$q.notify({ message: 'Edición simulada', color: 'warning', position: 'bottom-left' })"
            >
              <q-tooltip>Editar</q-tooltip>
            </q-btn>

            <q-btn
              size="sm"
              flat
              round
              color="negative"
              icon="delete"
              title="Eliminar"
              @click="onDeleteRow(props.row)"
            >
              <q-tooltip>Eliminar</q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
    <q-page-sticky position="bottom-right" :offset="[18, 18]">
      <q-btn 
        round 
        size="lg" 
        icon="add" 
        color="positive" 
        class="shadow-2"
        @click="$q.notify({ message: 'Alta simulada', color: 'warning', position: 'bottom-left' })"
      />
    </q-page-sticky>
  </AuthenticatedLayout>
</template>