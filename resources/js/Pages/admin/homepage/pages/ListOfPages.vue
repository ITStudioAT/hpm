<template>
    <!-- LIST OF PAGES -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-list nav density="comfortable">
                <v-list-subheader>Homepages</v-list-subheader>

                <v-list-item
                    v-for="(page, i) in pages"
                    :key="page.id ?? i"
                    :title="page.name"
                    :prepend-icon="'mdi-web'"
                    :active="isActive(page)"
                    @click="$emit('newActiveHomepage', page)" />
            </v-list>
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: ['pages', 'active_page'],
    emits: ['newActiveHomepage'],

    components: {},

    async beforeMount() {},

    unmounted() {},

    data() {
        return {}
    },

    computed: {},

    methods: {
        isActive(page) {
            return this.active_page?.name === page?.name
        },
    },
}
</script>
