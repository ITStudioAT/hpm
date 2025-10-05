<template>
    <!-- EDIT MENU -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-form ref="form" @submit.prevent="doUpdate(homepage, active_menu)" v-model="is_valid">
                <v-card flat tile color="primary">
                    <v-card-title class="d-flex flex-row align-center justify-space-between">
                        <div>Menü ändern</div>
                        <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                    </v-card-title>
                    <v-card-subtitle>Aktuelles Menü:</v-card-subtitle>
                    <v-card-text class="pt-4">
                        <v-treeview
                            v-model:activated="activeIdProxy"
                            color="primary"
                            :items="items"
                            item-key="id"
                            item-value="id"
                            activatable
                            open-all
                            v-if="root.children && root.children.length > 0">
                            <template v-slot:prepend="{ item, isOpen }">
                                <v-icon v-if="item.url" icon="mdi-link"></v-icon>
                                <v-icon v-if="item.page_id" icon="mdi-arrow-bottom-right-thick"></v-icon>
                            </template>
                        </v-treeview>
                        <div class="text-body-1 font-weight-medium" v-if="!root.children || root.children.length == 0">
                            Fügen Sie Menüpunkte hinzu!
                        </div>
                    </v-card-text>

                    <v-card-actions class="d-block">
                        <v-card
                            tile
                            flat
                            class="d-flex flex-row align-center justify-space-between"
                            color="primary"
                            :disabled="selected_menu_action != ''">
                            <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                            <v-btn flat tile variant="flat" color="success" type="submit">Speichern</v-btn>
                        </v-card>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-col>

        <!-- Edit Menu Actions-->
        <v-col cols="6" sm="3" md="2">
            <EditMenuActions :active_menu="active_menu" :homepage="homepage" />
        </v-col>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { shallowRef } from 'vue'
import { useValidationRulesSetup } from '@/helpers/rules'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useMenuStore } from '@/stores/admin/MenuStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import EditMenuActions from './EditMenuActions.vue'
export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['active_menu', 'homepage'],
    emits: ['abort'],

    components: { ItsMenuButton, EditMenuActions },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.menuStore = useMenuStore()

        this.root = this.active_menu.structure.root

        this.items = shallowRef([...this.root.children])
    },

    unmounted() {},

    data() {
        return {
            max_id: 0,
            test_node: null,
            data: {},
            adminStore: null,
            menuStore: null,
            is_valid: false,
        }
    },

    computed: {
        ...mapWritableState(useAdminStore, ['is_in_work']),
        ...mapWritableState(useMenuStore, [
            'menus',
            'selected_menu_action',
            'selected_action',
            'active_id',
            'root',
            'items',
        ]),
        activeIdProxy: {
            get() {
                // always return an array — if active_id is falsy, use [0]
                return [this.active_id || 0]
            },
            set(val) {
                // if val is empty or invalid, set to 0
                this.active_id = Array.isArray(val) && val.length > 0 ? val[0] : 0
            },
        },
    },

    methods: {
        async doUpdate(homepage, menu) {
            let answer = false
            answer = await this.menuStore.update(menu)

            console.log('ANSWER:' + answer)
            if (!answer) return

            await this.menuStore.index(homepage.id)
            console.log('a')
            this.selected_menu_action = ''
            this.selected_action = ''
        },
    },
}
</script>
