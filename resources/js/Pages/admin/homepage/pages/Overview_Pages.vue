<template>
    <!-- OVERVIEW HOMEPAGES -->
    <v-card tile flat :disabled="selected_action != ''">
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                title="Neue Seite"
                icon="mdi-plus-thick"
                :color="selected_action == 'new_page' ? 'primary' : 'secondary'"
                @click="newPage" />
        </v-row>
    </v-card>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useHomepageStore } from '@/stores/admin/HomepageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'

export default {
    components: { ItsMenuButton },

    async beforeMount() {
        this.homepageStore = useHomepageStore()
    },

    unmounted() {},

    data() {
        return {
            hompageStore: null,
            selected_action: '',
            data: {},
        }
    },

    computed: {
        ...mapWritableState(useHomepageStore, []),
    },

    methods: {},
}
</script>
