import '../css/app.css';

import {createInertiaApp, router} from '@inertiajs/vue3';
import Aura from '@primeuix/themes/aura';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import Tooltip from 'primevue/tooltip';
import type {DefineComponent} from 'vue';
import {createApp, h} from 'vue';

import {ZiggyVue} from 'ziggy-js';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

router.on('navigate', () => {
    const token = localStorage.getItem('auth_token');
    if (document.location.pathname.startsWith('/admin')) {
        if (!token) {
            router.visit(route('auth.login'));
        }
    }
    if (document.location.pathname.startsWith('/auth')) {
        if (token) {
            router.visit(route('main'));
        }
    }
})

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({el, App, props, plugin}) {

        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura
                }
            })
            .use(ZiggyVue)
            .directive('tooltip', Tooltip)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
