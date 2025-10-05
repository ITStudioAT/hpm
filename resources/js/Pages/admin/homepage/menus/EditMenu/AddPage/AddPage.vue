<template>
    <v-form ref="form" @submit.prevent="" v-model="is_valid">
        <v-card tile flat>
            <v-card-title class="d-flex flex-row align-center justify-space-between bg-secondary mb-4">
                <div>Seite hinzufügen</div>
            </v-card-title>
            <v-card-text>
                <Folders :homepage="homepage"></Folders>
                <ListOfPages :homepage="homepage"></ListOfPages>
            </v-card-text>
            <v-card-actions class="d-flex flex-row align-center justify-space-between">
                <v-btn flat tile variant="flat" color="warning" @click="doAbortMenuAction">Abbruch</v-btn>
                <v-btn flat tile variant="flat" color="success" type="submit" v-if="active_page_id" @click="doAddPage">
                    Hinzufügen
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
import { mapWritableState } from 'pinia'
import { useValidationRulesSetup } from '@/helpers/rules'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useMenuStore } from '@/stores/admin/MenuStore'
import { useFolderStore } from '@/stores/admin/FolderStore'
import { usePageStore } from '@/stores/admin/PageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import Folders from './Folders.vue'
import ListOfPages from './ListOfPages.vue'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['homepage'],
    emits: ['addPage'],

    components: { ItsMenuButton, Folders, ListOfPages },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.menuStore = useMenuStore()
        this.folderStore = useFolderStore()
        this.pageStore = usePageStore()
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            menuStore: null,
            folderStore: null,
            pageStore: null,
            is_valid: false,
        }
    },

    computed: {
        ...mapWritableState(useAdminStore, ['is_in_work']),
        ...mapWritableState(useMenuStore, ['selected_menu_action']),
        ...mapWritableState(useFolderStore, ['active_folder']),
        ...mapWritableState(usePageStore, ['active_page_id']),
    },

    methods: {
        doAddPage() {
            this.$emit('addPage', this.active_page_id)
        },
        doAbortMenuAction() {
            this.data = {}
            this.selected_menu_action = ''
        },
    },
}
</script>
