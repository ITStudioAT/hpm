// resources/js/homepage.js
import '../bootstrap.js'
import '../../css/fonts.css'
import '../../css/homepage.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { VueQueryPlugin, QueryClient } from '@tanstack/vue-query'  // ⬅️ add

import App from '../Pages/homepage/App.vue'
import vuetify from '../../plugins/homepage.js'
import router from '../../routes/homepage.js'

const pinia = createPinia()

// Create one QueryClient for the whole app
const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            staleTime: 60_000,
            refetchOnWindowFocus: false,
        },
    },
})

const app = createApp(App)
    .use(vuetify)
    .use(pinia)
    .use(router)
    .use(VueQueryPlugin, {
        queryClient,
        // enables integration with the official Vue Devtools (v6/v7) in dev
        enableDevtoolsV6Plugin: import.meta.env.DEV,
    })

router.isReady().then(() => {
    app.mount('#app')
})
