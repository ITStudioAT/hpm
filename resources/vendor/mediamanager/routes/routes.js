// resources/js/routes.js (inside your package)
export default [
    {
        path: '/admin/mm',
        name: 'media.index',
        component: () => import('../../../../vendor/itstudioat/mediamanager/resources/js/pages/admin/index/Index.vue'), // or actual path
        meta: {
            title: 'Media Manager',
        },
    },

    {
        path: '/hpm/admin/mm/select',
        name: 'media.select',
        component: () => import('../../../../vendor/itstudioat/mediamanager/resources/js/pages/admin/select/Select.vue'), // or actual path
        meta: {
            title: 'Media Manager Selection',
        },
    },
]
