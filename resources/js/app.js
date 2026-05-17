import '../css/tailwind.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// --- IMPORTACIONES DE QUASAR ---
import { Quasar, Dialog, Notify } from 'quasar';
import '@quasar/extras/material-icons/material-icons.css'; // Iconos
import 'quasar/src/css/index.sass'; // Estilos core de Quasar
// --------------------------------

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Quasar, {
                plugins: {
                    Dialog,
                    Notify,
                },
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
