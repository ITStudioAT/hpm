import '../bootstrap.js';
import '../../css/fonts.css';
import '../../css/admin.css';

import { createApp } from "vue"
import { createPinia } from 'pinia'

import App from "../Pages/admin/App.vue"

import vuetify from "../../plugins/admin.js"
import router from '../../routes/admin.js'

const pinia = createPinia()
var app = createApp(App).use(vuetify).use(pinia).use(router);

router.isReady().then(() => {
    app.mount('#app')
})