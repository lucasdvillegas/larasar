<template>
  <q-list 
    :padding="$q.screen.gt.sm"  
    :class="[
      'rounded-borders bg-white', 
      $q.screen.gt.sm ? 'q-my-md q-ml-md border' : ''
    ]"
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
      class="rounded-borders q-ma-sm"
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
      class="rounded-borders q-mx-sm q-mb-sm"
      :header-class="[
        'rounded-borders', 
        route().current('admin.blogs.*') ? 'text-primary' : ''
      ]"
      :value="route().current('admin.blogs.*')"
    >
      <template #header>
        <q-item-section avatar class="q-pr-sm text-grey-8" style="min-width: 24px;">
          <q-icon name="newspaper" />
        </q-item-section>

        <q-item-section class="text-grey-8">
          <div>
            Blogs
          </div>
        </q-item-section>
      </template>
      <q-list class="q-pl-md q-pt-xs">
        <q-item 
          v-ripple
          clickable 
          :href="route('admin.blogs.index')"
          :active="route().current('admin.blogs.index')"
          active-class="text-primary bg-blue-1"
          class="rounded-borders q-my-xs"
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
          class="rounded-borders q-my-xs"
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