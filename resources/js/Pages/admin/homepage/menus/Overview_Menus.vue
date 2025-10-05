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

    <!-- LIST OF MENUS -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters v-if="selected_action == ''">
        <v-col cols="12" sm="4" md="3">
            <ListOfMenus :menus="menus" :active_menu="active_menu" @newActiveMenu="active_menu = $event" />
        </v-col>

        <v-col cols="12" sm="4" md="3" v-if="active_menu">
            <v-card flat tile border>
                <v-card-title>
                    {{ active_menu.name }}
                </v-card-title>
                <v-card-text v-if="root.children && root.children.length > 0">
                    <v-treeview color="primary" :items="items" item-key="id" item-value="id" activatable open-all>
                        <template v-slot:prepend="{ item, isOpen }">
                            <v-icon v-if="item.url" icon="mdi-link"></v-icon>
                            <v-icon v-if="item.page_id" icon="mdi-arrow-bottom-right-thick"></v-icon>
                        </template>
                    </v-treeview>
                </v-card-text>
                <v-card-text v-else>
                    <div class="text-body-2">Menü ist leer</div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <!-- NEUES/UMBENENNEN MENÜ -->
    <v-row
        class="d-flex flex-row ga-2 mb-2 mt-0 w-100"
        no-gutters
        v-if="['new_menu', 'rename_menu'].includes(selected_action)">
        <v-col cols="12" sm="4" md="3">
            <Overview_NewEditMenu :data="data" :homepage="homepage" @save="doSave($event)" @abort="doAbort" />
        </v-col>
    </v-row>

    <!-- MENÜ BEARBEITEN-->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters v-if="selected_action == 'edit_menu'">
        <EditMenu :active_menu="active_menu" :homepage="homepage" @abort="doAbortEditMenu" />
    </v-row>
</template>
<script>
import { shallowRef } from 'vue'
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
            'root',
            'items',
        ]),
    },
    watch: {
        active_menu() {
            this.root = this.active_menu.structure.root
            this.items = shallowRef([...this.root.children])
        },
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
