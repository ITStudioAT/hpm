import { createRouter, createWebHistory } from 'vue-router'
import Index from '@/pages/homepage/index/Index.vue'
import Application_Error from '@/pages/application/Error.vue'
import Preview_Colors from '@/pages/homepage/special/PreviewColors.vue'
import Preview_Fonts from '@/pages/homepage/special/PreviewFonts.vue'


const routes = [
    { path: '/', component: Index },
    { path: '/homepage/error', component: Application_Error },
    { path: '/special/preview_colors', component: Preview_Colors },
    { path: '/special/preview_fonts', component: Preview_Fonts },


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