<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3' // Importamos herramientas de Inertia
import { useQuasar } from 'quasar'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import es from 'yup-es'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

yup.setLocale(es)

const props = defineProps({
  blog: {
    type: Object,
    required: true
  }
})

const $q = useQuasar()
const saving = ref(false)

const schema = yup.object({
  blog_title: yup.string().required().label('Título'),
  blog_status: yup.string().required().oneOf(['active', 'inactive']).label('Estado'),
  blog_description: yup.string().required().label('Descripción'),
  blog_content: yup.string().required().label('Contenido'),
})

const initialValues = ref({
  blog_title: props.blog.blog_title || '',
  blog_status: props.blog.blog_status || 'active',
  blog_description: props.blog.blog_description || '',
  blog_content: props.blog.blog_content || ''
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

const [blog_title, blogTitleProps] = defineField('blog_title', fieldConfig)
const [blog_status, blogStatusProps] = defineField('blog_status', fieldConfig)
const [blog_description, blogDescriptionProps] = defineField('blog_description', fieldConfig)
const [blog_content, blogContentProps] = defineField('blog_content', fieldConfig)

const statusOptions = [
  { label: 'Activo', value: 'active' },
  { label: 'Inactivo', value: 'inactive' }
]

const onSubmit = handleSubmit((values, actions) => {
  saving.value = true

  router.put(route('admin.blogs.update', props.blog.blog_slug), values, {
    onSuccess: () => {
      $q.notify({
        color: 'positive',
        position: 'top',
        message: 'Blog actualizado con éxito',
      })
    },
    onError: (backendErrors) => {
      if (backendErrors) {
        actions.setErrors(backendErrors)
      }
      
      $q.notify({
        color: 'negative',
        position: 'top',
        message: 'Los datos enviados no son válidos.',
        icon: 'mdi-alert'
      })
    },
    onFinish: () => {
      saving.value = false
    }
  })
}, () => {
  $q.notify({
    color: 'negative',
    position: 'top',
    message: 'Por favor revise los errores en el formulario',
    icon: 'mdi-alert'
  })
})
</script>

<template>
  <Head title="Modificar Blog" />
  <AuthenticatedLayout>
    <q-page padding class="flex justify-center items-start">
      <q-form style="width: 100%;" @submit="onSubmit">
        <q-card style="width: 100%;">
          <q-card-section>
            <div class="text-h6">
              Modificar Blog
            </div>
          </q-card-section>

          <q-card-section>
            <q-input
              v-model="blog_title"
              outlined
              dense
              label="Título"
              v-bind="blogTitleProps"
              :class="errors.blog_title ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-select
              v-model="blog_status"
              outlined
              dense
              label="Estado"
              :options="statusOptions"
              emit-value
              map-options
              v-bind="blogStatusProps"
              :class="errors.blog_status ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
              v-model="blog_description"
              outlined
              dense
              label="Descripción Corta"
              v-bind="blogDescriptionProps"
              :class="errors.blog_description ? 'q-mb-md' : 'q-mb-sm'"
            />

            <q-input
              v-model="blog_content"
              outlined
              dense
              type="textarea"
              label="Contenido del Blog"
              v-bind="blogContentProps"
              :class="errors.blog_content ? 'q-mb-md' : 'q-mb-sm'"
            />
          </q-card-section>

          <q-card-actions vertical align="center">
            <q-btn flat color="primary" type="submit" label="Guardar" :loading="saving" />          
            <q-btn flat label="Cancelar" :href="route('admin.blogs.index')" :disable="saving" />
          </q-card-actions>
        </q-card>
      </q-form>
    </q-page>
  </AuthenticatedLayout>
</template>