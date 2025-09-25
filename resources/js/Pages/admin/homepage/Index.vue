<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- Aktive Homepage -->
        <v-row v-if="active_homepage" class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <v-col>
                <v-card tile flat color="accent">
                    <v-card-title class="d-flex flex-row ga-2 justify-space-between">
                        <div>Aktive Homepage: {{ active_homepage.name }}</div>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>

        <!-- HAUPTMENÜ -->
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                title="Übersicht"
                icon="mdi-view-agenda"
                :color="selected_menu == 'homepages' ? 'primary' : 'secondary'"
                @click="doOverview" />

            <its-menu-button
                title="Seiten"
                icon="mdi-view-agenda"
                :color="selected_menu == 'pages' ? 'primary' : 'secondary'"
                @click="doPages" />
        </v-row>

        <!-- OVERVIEW HOMEPAGES -->
        <OverviewHomepages v-if="selected_menu == 'homepages'" />

        <!-- OVERVIEW PAGES -->
        <OverviewPages v-if="selected_menu == 'pages'" />
    </v-container>
</template>

<script>
import { mapWritableState } from 'pinia'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useHomepageStore } from '@/stores/admin/HomepageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import OverviewHomepages from './index/Overview_Homepages.vue'
import OverviewPages from './pages/Overview_Pages.vue'

export default {
    components: { ItsMenuButton, OverviewHomepages, OverviewPages },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.adminStore.initialize(this.$router)
        this.homepageStore = useHomepageStore()
        this.doOverview()
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            selected_menu: 'overview',
        }
    },

    computed: {
        ...mapWritableState(useAdminStore, [
            'config',
            'is_loading',
            'show_navigation_drawer',
            'load_config',
            'user_roles',
        ]),
        ...mapWritableState(useHomepageStore, ['active_homepage', 'homepages', 'delete_action']),
    },

    methods: {
        async doPages() {
            this.selected_menu = 'pages'
        },

        async doOverview() {
            await this.homepageStore.index()
            // Preselect first homepage if none is active
            if (!this.active_homepage && this.homepages?.length) {
                this.active_homepage = this.homepages[0]
            }
            this.delete_action = 0
            this.selected_menu = 'homepages'
        },
    },
}
</script>
