import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js'
import { route } from 'ziggy-js' // isso já é o helper de rotas
// Vuetify
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import '@fortawesome/fontawesome-free/css/all.css'

// exporta apenas o helper route
export { route };

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'weddingTheme',
        themes: {
            weddingTheme: {
                dark: false,
                colors: {
                    primary: '#0C2452',
                    secondary: '#B4CCE9',
                    accent: '#6F8FBF',

                    background: '#F6F9FE',
                    surface: '#FFFFFF',

                    error: '#B00020',
                    success: '#2E7D32',
                    warning: '#F9A825',
                    info: '#1E88E5',
                },
            },
        },
    },
})

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, props.ziggy) // ⬅️ aqui passamos o objeto Ziggy injetado pelo backend
            .use(vuetify)
            .mount(el)
    },
})
