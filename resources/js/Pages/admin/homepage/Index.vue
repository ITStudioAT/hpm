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
                @click="doHomepages" />

            <its-menu-button
                title="Seiten"
                icon="mdi-book-open-page-variant-outline"
                :color="selected_menu == 'pages' ? 'primary' : 'secondary'"
                @click="doPages" />

            <its-menu-button
                title="Menüs"
                icon="mdi-menu-open"
                :color="selected_menu == 'menus' ? 'primary' : 'secondary'"
                @click="doMenus" />
        </v-row>

        <!-- OVERVIEW HOMEPAGES -->
        <OverviewHomepages v-if="selected_menu == 'homepages'" />

        <!-- OVERVIEW PAGES -->
        <OverviewPages :homepage="active_homepage" v-if="selected_menu == 'pages' && active_homepage" />

        <!-- OVERVIEW MENUS -->
        <OverviewMenus :homepage="active_homepage" v-if="selected_menu == 'menus' && active_homepage" />
    </v-container>
</template>

<script>
import { mapWritableState } from 'pinia'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useHomepageStore } from '@/stores/admin/HomepageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import OverviewHomepages from './homepages/Overview_Homepages.vue'
import OverviewPages from './pages/Overview_Pages.vue'
import OverviewMenus from './menus/Overview_Menus.vue'

export default {
    components: { ItsMenuButton, OverviewHomepages, OverviewPages, OverviewMenus },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.adminStore.initialize(this.$router)
        this.homepageStore = useHomepageStore()
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            selected_menu: 'homepages',
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
        doHomepages() {
            this.selected_menu = 'homepages'
        },

        doPages() {
            this.selected_menu = 'pages'
        },

        doMenus() {
            this.selected_menu = 'menus'
        },
    },
}
</script>
