<template>
    <!-- Aktive Menu -->
    <v-row v-if="active_menu" class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col>
            <v-card tile flat color="accent-2">
                <v-card-title class="d-flex flex-row ga-2 justify-space-between">
                    <div>Aktives Menü: {{ active_menu.name }}</div>
                </v-card-title>
            </v-card>
        </v-col>
    </v-row>

    <!-- OVERVIEW PAGES -->
    <v-card tile flat :disabled="selected_action != ''">
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                title="Neues Menü"
                icon="mdi-plus-thick"
                :color="selected_action == 'new_menu' ? 'primary' : 'secondary'"
                @click="newMenu" />

            <its-menu-button
                title="Bearbeiten"
                icon="mdi-cog"
                :color="selected_action == 'edit_menu' ? 'primary' : 'success'"
                @click="editMenu(active_menu)"
                v-if="active_menu" />

            <its-menu-button
                title="Umbenennen"
                icon="mdi-pencil"
                :color="selected_action == 'rename_menu' ? 'primary' : 'secondary'"
                @click="renameMenu(active_menu)"
                v-if="active_menu" />

            <its-menu-button
                title="Nicht Löschen"
                icon="mdi-delete-off"
                color="success"
                @click="delete_action = 0"
                v-if="active_menu && delete_action == 1" />

            <its-menu-button
                title="Löschen"
                icon="mdi-delete"
                :color="delete_action > 0 ? 'error' : 'warning'"
                @click="delete_action == 0 ? delete_action++ : doDelete(active_menu)"
                v-if="active_menu" />
        </v-row>
    </v-card>

    <!-- LIST OF PAGES -->
    <ListOfMenus
        :menus="menus"
        :active_menu="active_menu"
        @newActiveMenu="active_menu = $event"
        v-if="selected_action == ''" />

    <!-- NEUES/UMBENENNEN MENÜ -->
    <Overview_NewEditMenu
        :data="data"
        :homepage="homepage"
        v-if="['new_menu', 'rename_menu'].includes(selected_action)"
        @save="doSave($event)"
        @abort="doAbort" />

    <!-- MENÜ BEARBEITEN-->
    <EditMenu
        :active_menu="active_menu"
        :homepage="homepage"
        v-if="selected_action == 'edit_menu'"
        @abort="doAbortEditMenu" />
</template>
<script>
import { mapWritableState } from 'pinia'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useMenuStore } from '@/stores/admin/MenuStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import Overview_NewEditMenu from './Overview_NewEditMenu.vue'
import ListOfMenus from './ListOfMenus.vue'
import EditMenu from './EditMenu/EditMenu.vue'

export default {
    props: ['homepage'],
    components: { ItsMenuButton, ListOfMenus, Overview_NewEditMenu, EditMenu },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.menuStore = useMenuStore()

        this.doOverview(this.homepage?.id)
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            menuStore: null,
            data: {},
        }
    },

    computed: {
        ...mapWritableState(useAdminStore, ['is_in_work']),
        ...mapWritableState(useMenuStore, [
            'active_menu',
            'menus',
            'delete_action',
            'selected_action',
            'selected_menu_action',
        ]),
    },

    methods: {
        editMenu() {
            this.is_in_work = true
            this.selected_action = 'edit_menu'
        },

        async doOverview(homepage_id) {
            await this.menuStore.index(homepage_id)
            // Preselect first homepage if none is active
            if (!this.active_menu && this.menus?.length) {
                this.active_menu = this.menus[0]
            }
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

        doAbortEditMenu() {
            this.selected_menu_action = ''
            this.selected_action = ''
            this.is_in_work = false
        },
        newMenu() {
            this.data = {}
            this.selected_action = 'new_menu'
        },

        renameMenu(menu) {
            this.data = JSON.parse(JSON.stringify(menu))
            this.selected_action = 'rename_menu'
        },
        async doDelete(menu) {
            await this.menuStore.delete(menu)
            await this.menuStore.index(this.homepage.id)
            this.active_menu = null
            this.delete_action = 0
        },
    },
}
</script>
