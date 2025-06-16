import '../bootstrap.js';
import '../../css/admin.css';
import '../../../mediamanager/css/mediamanager.css';

import { createApp } from "vue";
import { createPinia } from 'pinia';
import mediamanager from "../../../mediamanager/plugins/mediamanager.js";


import App from "../pages/admin/App.vue";
import vuetify from "../../plugins/admin.js";
import router from '../../routes/admin.js';


const pinia = createPinia();
const app = createApp(App).use(vuetify).use(mediamanager).use(pinia).use(router);
app.mount('#app');