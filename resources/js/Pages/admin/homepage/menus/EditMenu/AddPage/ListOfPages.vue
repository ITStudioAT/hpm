<template>
    <!-- LIST OF PAGES -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters v-if="pages">
        <v-col cols="12">
            <v-list nav density="comfortable" color="primary" v-model:selected="list_value">
                <v-list-subheader>Seiten</v-list-subheader>

                <v-list-item
                    v-for="(page, i) in pagesInFolder(pages, active_folder)"
                    :key="page.id ?? `${i}-${page.path}`"
                    :title="page.name"
                    :value="page.id"
                    :subtitle="`${page.type} • /${page.path}`"
                    lines="two"
                    @click.stop.prevent="$emit('newActivePage', page)" />
            </v-list>
        </v-col>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useFolderStore } from '@/stores/admin/FolderStore'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { usePageStore } from '@/stores/admin/PageStore'

export default {
    props: ['homepage'],
    emits: ['newActivePage', 'newActiveFolder'],

    components: {},

    async beforeMount() {
        this.folderStore = useFolderStore()
        this.pageStore = usePageStore()
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            folderStore: null,
            pageStore: null,
            list_value: null,
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
        ...mapWritableState(usePageStore, [
            'active_page',
            'active_page_id',
            'pages',
            'delete_action',
            'selected_action',
        ]),
    },

    watch: {
        async active_folder() {
            this.$emit('newActiveFolder')
        },
        list_value() {
            if (this.list_value.length > 0) this.active_page_id = this.list_value[0]
            else this.active_page_id = null
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
