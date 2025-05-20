import { createRouter, createWebHistory } from 'vue-router'
import Index from '../js/pages/homepage/index/Index.vue'



const routes = [
    { path: '/', component: Index },


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