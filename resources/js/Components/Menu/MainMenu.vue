<template>
  <q-list padding>
    <q-item-label header class="text-grey-7 text-bold">
      Navegación
    </q-item-label>

    <q-item 
      v-ripple 
      clickable
      :active="route().current('dashboard')"
      active-class="bg-blue-1 text-primary"
      :href="route('dashboard')"
    >
      <q-item-section side class="q-pr-sm">
        <q-icon name="dashboard" :color="route().current('dashboard') ? 'primary' : 'grey-7'" />
      </q-item-section>
      <q-item-section>
        Dashboard
      </q-item-section>
    </q-item>

    <q-item 
      v-ripple 
      clickable 
      :active="route().current('admin.blogs.*')"
      active-class="text-primary bg-blue-1"
      :href="route('admin.blogs.index')"
    >
      <q-item-section side class="q-pr-sm">
        <q-icon name="newspaper" :color="route().current('admin.blogs.*') ? 'primary' : 'grey-7'" />
      </q-item-section>

      <q-item-section>
        Blogs
      </q-item-section>
    </q-item>
  </q-list>
</template>