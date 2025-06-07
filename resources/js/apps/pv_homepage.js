import '../bootstrap.js';
import '../../css/pv_homepage.css';

import { createApp } from "vue";
import { createPinia } from 'pinia';


import App from "../pages/pv_homepage/App.vue";
import vuetify from "../../plugins/pv_homepage.js";
import router from '../../routes/pv_homepage.js';


const pinia = createPinia();
const app = createApp(App).use(vuetify).use(pinia).use(router);
app.mount('#app');