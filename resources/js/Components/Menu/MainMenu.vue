<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useQuasar } from 'quasar'

const page = usePage()
const $q = useQuasar()

const menus = computed(() => page.props.menus || [])

function navigate(routeName) {
  if (routeName) {
    router.visit(route(routeName))
  }
}
</script>

<template>
  <q-list
    :padding="$q.screen.gt.sm"
    :class="[
      'bg-white',
      $q.screen.gt.sm ? 'q-my-md q-ml-md shadow-2' : ''
    ]"
    :style="$q.screen.gt.sm ? 'border-radius: 4px;' : ''"
  >
    <template 
      v-for="menu in menus" 
      :key="menu.id"
    >
      
      <!-- menu -->
      <q-item
        v-if="!menu.children || menu.children.length === 0"
        dense
        clickable
        :active="menu.route_pattern ? route().current(menu.route_pattern) : false"
        active-class="bg-blue-1 text-primary"
        @click="navigate(menu.route_name)"
        class="q-ma-sm"
        style="border-radius: 4px;"
      >
        <q-item-section side class="q-pr-sm">
          <q-icon
            :name="menu.icon || 'link'"
            :color="menu.route_pattern && route().current(menu.route_pattern) ? 'primary' : 'grey-8'"
            class="menu-icon"
          />
        </q-item-section>
        <q-item-section>
          {{ menu.label }}
        </q-item-section>
      </q-item>

      <!-- submenús -->
      <q-expansion-item
        v-else
        dense
        :icon="menu.icon"
        :label="menu.label"
        class="q-mx-sm q-mb-sm"
        style="border-radius: 4px;"
        :header-class="[
          menu.route_pattern && route().current(menu.route_pattern) ? 'text-primary' : ''
        ]"
        :model-value="menu.route_pattern ? route().current(menu.route_pattern) : false"
      >
        <template #header>
          <q-item-section avatar class="q-pr-sm text-grey-9" style="min-width: 24px;">
            <q-icon
              :name="menu.icon || 'folder'"
              :color="menu.route_pattern && route().current(menu.route_pattern) ? 'primary' : 'grey-9'"
              class="menu-icon"
            />
          </q-item-section>
          <q-item-section>
            <div>{{ menu.label }}</div>
          </q-item-section>
        </template>

        <q-list class="q-pl-md q-pt-xs">
          <q-item
            v-for="child in menu.children"
            :key="child.id"
            v-ripple
            clickable
            @click="navigate(child.route_name)"
            :active="child.route_pattern ? route().current(child.route_pattern) : false"
            active-class="text-primary bg-blue-1"
            class="q-my-xs"
            style="border-radius: 4px;"
            dense
          >
            <q-item-section side class="q-pr-xs">
              <q-icon
                class="q-mr-sm menu-icon"
                :name="child.icon || 'link'"
                size="18px"
                :color="child.route_pattern && route().current(child.route_pattern) ? 'primary' : ''"
              />
            </q-item-section>
            <q-item-section>
              {{ child.label }}
            </q-item-section>
          </q-item>
        </q-list>
      </q-expansion-item>

    </template>
  </q-list>
</template>

<style scoped>
/* Transición suave para todos los iconos que tengan esta clase */
.menu-icon {
  transition: transform 0.10s ease-in-out!important;
}

/* El icono escala cuando el padre (q-item) recibe hover */
.q-item:hover .menu-icon,
.q-expansion-item__header:hover .menu-icon {
  transform: scale(1.1)!important;
}
</style>