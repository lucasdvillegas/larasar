<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import MainMenu from '@/Components/Menu/MainMenu.vue';

const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

function logout() {
  router.post(route('logout'));
}

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-1">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          LvBlog.
        </q-toolbar-title>

        <q-space />

        <q-btn flat no-caps icon="account_circle" :label="user.name">
          <q-menu fit anchor="bottom right" self="top right">
            <q-list style="min-width: 150px">
              <q-item 
                v-close-popup 
                clickable
                :href="route('profile.edit')"
              >
                <q-item-section side class="q-pr-sm">
                  <q-icon name="person" size="xs" />
                </q-item-section>
                <q-item-section>
                  Perfil
                </q-item-section>
              </q-item>

              <q-separator />

              <q-item 
                v-close-popup 
                clickable
                class="text-negative"
                @click="logout"
              >
                <q-item-section side class="q-pr-sm text-negative">
                  <q-icon name="logout" size="xs" />
                </q-item-section>
                <q-item-section>
                  Log Out
                </q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-white"
    >
      <MainMenu />
    </q-drawer>

    <q-page-container>
      <div v-if="$slots.header" class="bg-white q-pa-md">
        <div class="q-mx-auto">
          <slot name="header" />
        </div>
      </div>

      <slot />
    </q-page-container>
  </q-layout>
</template>