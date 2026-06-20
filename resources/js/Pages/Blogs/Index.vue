<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { Head, router, usePage } from '@inertiajs/vue3'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const $q = useQuasar()
const page = usePage()

const columns = [
  {
    name: 'id',
    required: true,
    label: 'ID',
    align: 'left',
    field: 'id',
    sortable: true
  },
  {
    name: 'blog_title',
    required: true,
    label: 'Título',
    align: 'left',
    field: 'blog_title',
    sortable: true
  },
  {
    name: 'blog_date',
    required: true,
    label: 'Fecha',
    format: val => new Date(val).toLocaleDateString(),
    align: 'left',
    field: 'blog_date',
    sortable: true
  },
  {
    name: 'blog_status',
    required: true,
    label: 'Estado',
    align: 'left',
    field: 'blog_status',
    sortable: true
  },
  {
    name: 'actions',
    label: '',
  },
]

const rows = ref(page.props.blogs || [])
const filter = ref('')
const statusFilter = ref(page.props.filters?.status || null)

const statusOptions = [
  { label: 'Todos', value: null },
  { label: 'Activo', value: 'active' },
  { label: 'Inactivo', value: 'inactive' },
]
const loading = ref(false)
const saving = ref(false)
const pagination = ref({
  sortBy: page.props.filters?.sortBy || 'id',
  descending: page.props.filters?.descending ?? true,
  page: page.props.paginationData?.current_page || 1,
  rowsPerPage: page.props.paginationData?.per_page || 10,
  rowsNumber: page.props.paginationData?.total || 0,
})

function onRequest (props) {
  loading.value = true

  const { page: pageNum, rowsPerPage, sortBy, descending } = props.pagination

  router.get(route('admin.blogs.index'), {
    page: pageNum,
    rowsPerPage: rowsPerPage,
    sortBy: sortBy,
    descending: descending,
    filter: props.filter,
    status: statusFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      rows.value = page.props.blogs || []
      
      pagination.value.rowsNumber = parseInt(page.props.paginationData?.total || 0)
      pagination.value.page = pageNum
      pagination.value.rowsPerPage = rowsPerPage
      pagination.value.sortBy = sortBy
      pagination.value.descending = descending

      loading.value = false
    }
  })
}

function onDeleteRow(row) {
  $q.dialog({
    title: 'Confirmar',
    message: '¿Está seguro que quiere borrar el registro?',
    cancel: true,
    persistent: true,
    ok: {
      label: 'Aceptar',
      color: 'negative',
      flat: true
    },
    cancel: {
      label: 'Cancelar',
      color: 'dark',
      flat: true
    }
  }).onOk(() => {
    saving.value = true

    router.delete(route('admin.blogs.destroy', row.blog_slug), {
      onSuccess: () => {
        $q.notify({
          type: 'positive',
          position: 'top',
          message: page.props.flash.success || 'El registro fue eliminado con éxito'
        })
        
        pagination.value.page = 1
        
        onRequest({
          pagination: pagination.value,
          filter: filter.value,
          status: statusFilter.value,
        })
      },
      onError: () => {
        $q.notify({
          type: 'negative',
          position: 'top',
          message: page.props.flash.error || 'Ocurrió un error inesperado al intentar eliminar el blog.',
        })
      },
      onFinish: () => {
        saving.value = false
      }
    })
  })
}

</script>

<template>
  <Head title="Blogs" />

  <AuthenticatedLayout>
    <q-page class="q-pa-md">
      <q-table
        v-model:pagination="pagination"
        flat
        bordered
        title="Blogs"
        :rows="rows"
        :columns="columns"
        row-key="id"
        :loading="loading"
        :filter="filter"
        binary-state-sort
        :rows-per-page-options="[10, 20, 50]"
        @request="onRequest"
      >
        <template #top-right>
          <div class="row q-gutter-sm">
            <q-select
              v-model="statusFilter"
              :options="statusOptions"
              option-value="value"
              option-label="label"
              emit-value
              map-options
              outlined
              dense
              label="Estado"
              style="min-width: 130px"
              @update:model-value="onRequest({ pagination: { ...pagination, page: 1 }, filter })"
            />
            <q-input v-model="filter" outlined dense debounce="300" placeholder="Buscar">
              <template #append>
                <q-icon name="search" /> 
              </template>

              <q-tooltip>Buscar por título</q-tooltip>
            </q-input>
          </div>
        </template>

        <template #body-cell-blog_status="props">
          <q-td :props="props">
            <q-badge 
              :color="props.row.blog_status === 'active' ? 'positive' : 'negative'"
              outline
            >
              {{ props.row.blog_status === 'active' ? 'Activo' : 'Inactivo' }}
            </q-badge>
          </q-td>
        </template>

        <template #body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              size="sm"
              flat
              round
              color="primary"
              icon="edit"
              title="Editar"
              @click="router.visit(route('admin.blogs.edit', props.row.blog_slug))"
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
    </q-page>
  </AuthenticatedLayout>
</template>