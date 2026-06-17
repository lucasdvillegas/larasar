<template>
  <q-list 
    :padding="$q.screen.gt.sm"  
    :class="[
      'bg-white', 
      $q.screen.gt.sm ? 'q-my-md q-ml-md' : ''
    ]"
    :style="$q.screen.gt.sm ? 'border: 1px solid #e0e0e0; border-radius: 4px;' : ''"
  >
    <q-item-label header class="text-grey-8 text-bold">
      Navegación
    </q-item-label>

    <q-item 
      v-ripple 
      clickable
      :active="route().current('dashboard')"
      active-class="bg-blue-1 text-primary"
      :href="route('dashboard')"
      class="q-ma-sm"
      style="border-radius: 4px;"
    >
      <q-item-section side class="q-pr-sm">
        <q-icon name="dashboard" :color="route().current('dashboard') ? 'primary' : 'grey-8'" />
      </q-item-section>
      <q-item-section>
        Dashboard
      </q-item-section>
    </q-item>

    <q-expansion-item
      icon="newspaper"
      label="Blogs"
      class="q-mx-sm q-mb-sm"
      style="border-radius: 4px;"
      :header-class="[
        route().current('admin.blogs.*') ? 'text-primary' : ''
      ]"
      :model-value="route().current('admin.blogs.*')"
    >
      <template #header>
        <q-item-section avatar class="q-pr-sm text-grey-8" style="min-width: 24px;">
          <q-icon name="newspaper" />
        </q-item-section>

        <q-item-section class="text-grey-8">
          <div>Blogs</div>
        </q-item-section>
      </template>

      <q-list class="q-pl-md q-pt-xs">
        <q-item 
          v-ripple
          clickable 
          :href="route('admin.blogs.index')"
          :active="route().current('admin.blogs.index')"
          active-class="text-primary bg-blue-1"
          class="q-my-xs"
          style="border-radius: 4px;"
          dense
        >
          <q-item-section side class="q-pr-xs">
            <q-icon class="q-mr-sm" name="list" size="18px" :color="route().current('admin.blogs.index') ? 'primary' : 'grey-6'" />
          </q-item-section>
          <q-item-section>
            Listado
          </q-item-section>
        </q-item>

        <q-item 
          v-ripple
          clickable 
          :href="route('admin.blogs.create')"
          :active="route().current('admin.blogs.create')"
          active-class="text-primary bg-blue-1"
          class="q-my-xs"
          style="border-radius: 4px;"
          dense
        >
          <q-item-section side class="q-pr-xs">
            <q-icon class="q-mr-sm" name="add_circle" size="18px" :color="route().current('admin.blogs.create') ? 'primary' : 'grey-6'" />
          </q-item-section>
          <q-item-section>
            Crear blog
          </q-item-section>
        </q-item>
      </q-list>
    </q-expansion-item>
  </q-list>
</template>