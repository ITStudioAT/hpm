<template>
    <!-- LIST OF PAGES -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters v-if="pages">
        <v-col cols="12" sm="4" md="3">
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
        </v-col>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useFolderStore } from '@/stores/admin/FolderStore'

export default {
    props: ['pages', 'active_page', 'homepage'],
    emits: ['newActivePage'],

    components: {},

    async beforeMount() {
        this.folderStore = useFolderStore()
    },

    unmounted() {},

    data() {
        return {
            folderStore: null,
        }
    },

    computed: {
        ...mapWritableState(useFolderStore, ['active_folder', 'delete_count']),
    },

    methods: {
        isActive(page) {
            return this.active_page?.name === page?.name
        },

        pagesInFolder(pages, folder) {
            return this.pages.filter((page) => page.folder === folder)
        },
    },
}
</script>
