<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
    leftDrawerOpen.value = !leftDrawerOpen.value;
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
                    Mi Aplicación
                </q-toolbar-title>

                <q-space />

                <q-btn flat no-caps icon="account_circle" :label="user.name">
                    <q-menu fit anchor="bottom right" self="top right">
                        <q-list style="min-width: 150px">
                            
                            <q-item clickable v-close-popup>
                                <q-item-section avatar>
                                    <q-icon name="person" size="xs" />
                                </q-item-section>
                                <q-item-section>
                                    <Link :href="route('profile.edit')" class="text-black full-width">
                                        Perfil
                                    </Link>
                                </q-item-section>
                            </q-item>

                            <q-separator />

                            <q-item 
                                clickable 
                                v-close-popup
                            >
                                <q-item-section avatar class="text-negative">
                                    <q-icon name="logout" size="xs" />
                                </q-item-section>
                                <q-item-section>
                                    <Link 
                                        :href="route('logout')" 
                                        method="post" 
                                        as="button" 
                                        class="text-negative text-left full-width bg-transparent q-pa-none cursor-pointer"
                                    >
                                        Log Out
                                    </Link>
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
            <q-list padding>
                <q-item-label header class="text-grey-7 text-bold">
                    Navegación
                </q-item-label>

                <q-item 
                    clickable 
                    v-ripple
                    :active="route().current('dashboard')"
                    active-class="bg-blue-1 text-primary"
                >
                    <q-item-section avatar>
                        <q-icon name="dashboard" />
                    </q-item-section>
                    <q-item-section>
                        <Link :href="route('dashboard')" class="full-width">
                            Dashboard
                        </Link>
                    </q-item-section>
                </q-item>

                <q-item 
                    clickable 
                    v-ripple
                    :active="route().current('clients.index')"
                    active-class="bg-blue-1 text-primary"
                >
                    <q-item-section avatar>
                        <q-icon name="people" /> 
                    </q-item-section>
                    <q-item-section>
                        <Link :href="route('clients.index')" class="text-inherit text-decoration-none full-width">
                            Clientes
                        </Link>
                    </q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            
            <div v-if="$slots.header" class="bg-white q-pa-md">
                <div class="q-mx-auto">
                    <slot name="header" />
                </div>
            </div>

            <q-page padding class="q-mx-auto full-width">
                <slot />
            </q-page>

        </q-page-container>
    </q-layout>
</template>