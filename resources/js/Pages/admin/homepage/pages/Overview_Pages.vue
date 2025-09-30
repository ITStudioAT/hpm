<template>
    <!-- FOLDERS -->
    <Folders :homepage="homepage" />

    <!-- Aktive Page -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col>
            <v-card tile flat :color="active_page ? 'accent-2' : 'warning'">
                <v-card-title class="d-flex flex-row ga-2 justify-space-between">
                    <div v-if="active_page">Aktive Seite: {{ active_page.name }}</div>
                    <div v-else>Bitte Seite auswählen</div>
                </v-card-title>
            </v-card>
        </v-col>
    </v-row>

    <!-- OVERVIEW PAGES -->
    <v-card tile flat :disabled="selected_action != ''">
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                title="Neue Seite"
                icon="mdi-plus-thick"
                :color="selected_action == 'new_page' ? 'primary' : 'secondary'"
                @click="newPage" />

            <its-menu-button
                title="Umbenennen"
                icon="mdi-pencil"
                :color="selected_action == 'rename_page' ? 'primary' : 'secondary'"
                @click="renamePage(active_page)"
                v-if="active_page" />

            <its-menu-button
                title="Nicht Löschen"
                icon="mdi-delete-off"
                color="success"
                @click="delete_action = 0"
                v-if="active_page && delete_action == 1" />

            <its-menu-button
                title="Löschen"
                icon="mdi-delete"
                :color="delete_action > 0 ? 'error' : 'warning'"
                @click="delete_action == 0 ? delete_action++ : doDelete(active_page)"
                v-if="active_page" />
        </v-row>
    </v-card>

    <!-- LIST OF PAGES -->
    <ListOfPages
        :pages="pages"
        :active_page="active_page"
        :homepage="homepage"
        @newActivePage="active_page = $event"
        @newActiveFolder="doOverview(homepage.id)"
        v-if="selected_action == ''" />

    <!-- NEUE/EDIT PAGE -->
    <Overview_NewEditPage
        :data="data"
        :homepage="homepage"
        v-if="['new_page', 'rename_page'].includes(selected_action)"
        @save="doSave($event)"
        @abort="doAbort" />
</template>
<script>
import { mapWritableState } from 'pinia'
import { usePageStore } from '@/stores/admin/PageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'

import Overview_NewEditPage from './Overview_NewEditPage.vue'
import ListOfPages from './ListOfPages.vue'
import Folders from './Folders/Folders.vue'

export default {
    props: ['homepage'],
    components: { ItsMenuButton, ListOfPages, Overview_NewEditPage, Folders },

    async beforeMount() {
        this.pageStore = usePageStore()

        this.doOverview(this.homepage?.id)
    },

    unmounted() {},

    data() {
        return {
            pageStore: null,
            data: {},
        }
    },

    computed: {
        ...mapWritableState(usePageStore, ['active_page', 'pages', 'delete_action', 'selected_action']),
    },

    methods: {
        async doOverview(homepage_id) {
            await this.pageStore.index(homepage_id)
            // Preselect first homepage if none is active
            this.delete_action = 0
            this.selected_action = ''
        },
        async doSave(data) {
            this.data = {}
            this.selected_action = ''
        },

        async doAbort() {
            await this.doOverview(this.homepage.id)
            this.data = {}
            this.selected_action = ''
        },
        newPage() {
            this.data = {}
            this.selected_action = 'new_page'
        },

        renamePage(page) {
            this.data = JSON.parse(JSON.stringify(page))
            this.selected_action = 'rename_page'
        },
        async doDelete(page) {
            await this.pageStore.delete(page)
            await this.pageStore.index(this.homepage.id)
            this.active_page = null
            this.delete_action = 0
        },
    },
}
</script>
