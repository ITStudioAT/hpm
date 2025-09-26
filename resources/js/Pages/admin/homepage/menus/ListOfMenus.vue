<template>
    <!-- LIST OF HOMEPAGES -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
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
        </v-col>
    </v-row>
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
