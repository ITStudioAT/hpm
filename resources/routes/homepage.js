import { createRouter, createWebHistory } from 'vue-router'
import Index from '@/pages/homepage/index/Index.vue'
import Application_Error from '@/pages/application/Error.vue'
import Example_ColorsAndFonts from '@/pages/homepage/examples/ColorsAndFonts.vue'


const routes = [
    { path: '/', component: Index },
    { path: '/homepage/example/colors_and_fonts', component: Example_ColorsAndFonts },
    { path: '/homepage/error', component: Application_Error },


];

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach(async (to, from, next) => {

    next();
    return;



})


export default router;