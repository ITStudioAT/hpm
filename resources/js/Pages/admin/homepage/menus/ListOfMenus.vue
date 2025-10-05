<template>
    <v-card tile flat border>
        <v-list nav density="comfortable" color="primary">
            <v-list-subheader>Menüs</v-list-subheader>

            <v-list-item
                v-for="(menu, i) in menus"
                :key="menu.id ?? i"
                :title="menu.name"
                :prepend-icon="'mdi-web'"
                :active="isActive(menu)"
                @click="$emit('newActiveMenu', menu)" />
        </v-list>
    </v-card>
</template>

<script>
export default {
    props: ['menus', 'active_menu'],
    emits: ['newActiveMenu'],

    components: {},

    async beforeMount() {},

    unmounted() {},

    data() {
        return {}
    },

    computed: {},

    methods: {
        isActive(menu) {
            return this.active_menu?.name === menu?.name
        },
    },
}
</script>
