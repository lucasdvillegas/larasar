<script setup>
import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { Head } from '@inertiajs/vue3';
import { router, usePage } from '@inertiajs/vue3'

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

const rows = ref([])
const filter = ref('')
const loading = ref(true)
const saving = ref(false)
const pagination = ref({
  sortBy: 'id',
  descending: true,
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

function onRequest (props) {
  loading.value = true

  const { page: pageNum, rowsPerPage, sortBy, descending } = props.pagination

  router.get(window.location.pathname, {
    page: pageNum,
    rowsPerPage: rowsPerPage,
    sortBy: sortBy,
    descending: descending,
    filter: props.filter,
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
    persistent: true
  }).onOk(() => {
    saving.value = true

    router.delete(`/blogs/${row.id}`, {
      onSuccess: () => {
        $q.notify({
          color: 'positive',
          position: 'top',
          message: 'El registro fue eliminado con éxito'
        })
        
        pagination.value.page = 1
        
        onRequest({
          pagination: pagination.value,
          filter: filter.value
        })
      },
      onError: () => {
        $q.notify({
          color: 'negative',
          position: 'top',
          message: 'Disculpe, falló la llamada al servidor.',
          icon: 'mdi-alert'
        })
      },
      onFinish: () => {
        saving.value = false
      }
    })
  })
}

onMounted(() => {  
    onRequest({
      pagination: pagination.value,
      filter: filter.value
    })
})
</script>

<template>
  <Head title="Blogs" />

  <AuthenticatedLayout>
    <q-page padding style="padding-bottom:110px">
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
          <q-input v-model="filter" outlined dense debounce="300" placeholder="Buscar">
            <template #append>
              <q-icon name="mdi-magnify" />
            </template>
          </q-input>
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

      <q-page-sticky position="bottom-right" :offset="[18, 18]">
        <q-btn 
          round 
          size="xl" 
          icon="add" 
          color="positive" 
          @click="router.visit(route('admin.blogs.create'))"
        />
      </q-page-sticky>
    </q-page>
  </AuthenticatedLayout>
</template>