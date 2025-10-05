<template>
    <v-row>
        <v-col class="d-flex flex-row ga-2">
            <v-btn
                icon="mdi-folder-arrow-right-outline"
                flat
                size="small"
                color="secondary"
                @click="startMove"
                v-if="!is_move_pages && pagesInFolder(pages, active_folder).length > 0" />

            <v-btn icon="mdi-close" flat size="small" color="warning" @click="doAbort" v-if="is_move_pages" />
            <v-btn
                flat
                tile
                color="secondary"
                text="Aktive Seite verschieben"
                @click="moveAction(active_page)"
                v-if="active_page && is_move_pages && !move_action"></v-btn>
            <v-btn
                flat
                tile
                color="secondary"
                text="Alle Seiten verschieben"
                v-if="is_move_pages && !move_action"
                @click="moveAction()"></v-btn>
            <div v-if="move_action" class="text-primary text-h6">
                Ordner auswählen und mit
                <v-icon icon="mdi-check" color="success" variant="tonal" />
                bestätigen!
            </div>
        </v-col>
    </v-row>
    <!-- LIST OF PAGES -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters v-if="pages">
        <v-col cols="12" sm="4" md="3">
            <v-card tile flat border>
                <v-list nav density="comfortable" color="primary">
                    <v-list-subheader>Seiten</v-list-subheader>

                    <v-list-item
                        v-for="(page, i) in pagesInFolder(pages, active_folder)"
                        :key="page.id ?? i"
                        :prepend-icon="'mdi-book-open-page-variant'"
                        :active="isActive(page)"
                        @click="$emit('newActivePage', page)">
                        <template #title>{{ page.name }}</template>
                        <template #subtitle>
                            <div class="d-flex flex-row align-center justify-space-between">
                                <div>{{ page.type }}</div>
                                <div>{{ '/' + page.path }}</div>
                            </div>
                        </template>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useFolderStore } from '@/stores/admin/FolderStore'
import { useAdminStore } from '@/stores/admin/AdminStore'

export default {
    props: ['pages', 'active_page', 'homepage'],
    emits: ['newActivePage', 'newActiveFolder'],

    components: {},

    async beforeMount() {
        this.folderStore = useFolderStore()
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            folderStore: null,
        }
    },

    computed: {
        ...mapWritableState(useFolderStore, [
            'active_folder',
            'delete_count',
            'folder_id',
            'move_action',
            'folder_90',
            'page_to_move',
            'is_move_pages',
        ]),
        ...mapWritableState(useAdminStore, ['is_in_work']),
    },

    watch: {
        async active_folder() {
            this.$emit('newActiveFolder')
        },
    },

    methods: {
        doAbort() {
            this.is_move_pages = false
            this.is_in_work = false
            this.move_action = ''
        },
        moveAction(page = null) {
            this.folder_90 = this.active_folder
            if (page) {
                this.move_action = 'active'
                this.page_to_move = page
            } else {
                this.move_action = 'all'
                this.page_to_move = null
            }
        },
        isActive(page) {
            return this.active_page?.name === page?.name
        },

        pagesInFolder(pages, folder) {
            return this.pages.filter((page) => page.folder === folder)
        },
        startMove() {
            this.is_move_pages = true
            this.is_in_work = true
        },
    },
}
</script>
