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
                    // Paleta 1 - Cores da Imagem (kiss.jpg)
                    primary: '#4A7C59',      // Verde Montanha
                    secondary: '#5B8FA3',    // Azul Lago
                    accent: '#C9B5A0',       // Bege Elegante
                    error: '#C85A54',        // Vermelho Suave
                    info: '#78A89A',         // Aqua
                    success: '#6B9B7F',      // Verde Médio
                    warning: '#D4A574',      // Dourado Suave
                    background: '#F8F9FA',   // Branco Gelo
                    surface: '#FFFFFF',      // Branco Puro

                    // Cores adicionais personalizadas
                    'on-primary': '#FFFFFF',
                    'on-secondary': '#FFFFFF',
                    'on-accent': '#1D1D1F',
                    'on-background': '#1D1D1F',
                    'on-surface': '#1D1D1F',
                },
            },
            // Tema Dark (opcional) - baseado nas mesmas cores
            weddingDark: {
                dark: true,
                colors: {
                    primary: '#6B9B7F',      // Verde mais claro para dark mode
                    secondary: '#78A89A',    // Aqua
                    accent: '#D4A574',       // Dourado
                    error: '#D4A5A5',        // Rosa suave
                    info: '#5B8FA3',
                    success: '#4A7C59',
                    warning: '#E8C9A3',
                    background: '#1A1A1A',
                    surface: '#2C2C2C',
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
