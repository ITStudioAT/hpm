<template>
    <v-row no-gutters class="mb-2" v-if="active_folder">
        <v-card
            flat
            border
            :disabled="['new_folder', 'rename_folder'].includes(selected_action) || pageStore.selected_action != ''"
            style="overflow-x: auto; white-space: nowrap">
            <v-card-title class="d-flex flex-row align-center justify-space-between bg-accent-2 mb-2">
                <div class="d-flex flex-row align-end">
                    <v-icon icon="mdi-map-marker" size="small" class="mr-2" />
                    <div class="text-body-2 font-weight-medium" :class="delete_count == 1 ? 'bg-error' : ''">
                        {{ active_folder }}
                    </div>
                </div>
                <div
                    class="d-flex flex-row align-center ga-2"
                    v-if="active_folder != '/' || (is_move_pages && move_action)">
                    <!-- Folder bestätigen -->
                    <v-btn
                        icon="mdi-check"
                        flat
                        size="small"
                        color="success"
                        @click="movePages"
                        v-if="is_move_pages && move_action" />
                </div>
            </v-card-title>
            <v-card-text>
                <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                    <v-col class="d-flex flex-row align-center ga-2">
                        <its-folder
                            title="Home"
                            :is_path="active_folder.startsWith('/')"
                            :is_active="active_folder == '/'"
                            @click="active_folder = '/'" />
                    </v-col>
                </v-row>

                <div v-if="active_folder && slashCount(active_folder) > 0 && active_folder != '/'">
                    <v-row no-gutters v-for="i in slashCount(active_folder)">
                        <v-col class="d-flex flex-row align-center">
                            <its-folder
                                :title="lastFolderPart(folder)"
                                :is_path="startsWith(active_folder, folder)"
                                :is_active="active_folder == folder"
                                :delete_count="delete_count"
                                @click="newActiveFolder(folder)"
                                class="mr-2 mb-2"
                                v-for="folder in getFolderWithCountSlashes(active_folder, folders, i).filter(
                                    (f) => f !== '/'
                                )" />
                        </v-col>
                    </v-row>
                </div>

                <v-row no-gutters v-if="active_folder">
                    <v-col class="d-flex flex-row align-center ga-2">
                        <its-folder
                            :title="lastFolderPart(folder)"
                            :is_active="false"
                            v-for="(folder, i) in getSubfolders(active_folder, folders)"
                            @click="newActiveFolder(folder)" />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useFolderStore } from '@/stores/admin/FolderStore'
import { usePageStore } from '@/stores/admin/PageStore'
import ItsFolder from '@/pages/components/ItsFolder.vue'

export default {
    emits: ['newActiveFolder', 'save', 'pagesMoved'],
    props: ['homepage'],
    components: { ItsFolder },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.pageStore = usePageStore()
        this.folderStore = useFolderStore()

        await this.folderStore.index(this.homepage.id, 'page_folders')
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            folderStore: null,
            pageStore: null,
            data: {},
            path: '',
            type: 'page_folders',
        }
    },

    computed: {
        ...mapWritableState(useFolderStore, [
            'folders',
            'active_folder',
            'selected_action',
            'folder_id',
            'delete_count',
            'move_action',
            'folder_90',
            'page_to_move',
            'is_move_pages',
        ]),
        ...mapWritableState(usePageStore, ['active_page', 'pages']),
        ...mapWritableState(useAdminStore, ['is_in_work']),
    },

    methods: {
        startsWith(path, item) {
            return path.startsWith(item) && (item.length === path.length || path.charAt(item.length) === '/')
        },

        newActiveFolder(folder) {
            this.active_page = null
            this.active_folder = folder
            this.delete_count = 0
            this.$emit('newActiveFolder', folder)
        },

        async doAbort() {
            await this.folderStore.index(this.homepage.id, this.type)
            this.data = {}
            this.selected_action = ''
        },

        getMatchingPrefix(arr, str) {
            let bestMatch = null

            for (const path of arr) {
                if (str.startsWith(path)) {
                    // keep the longest one
                    if (!bestMatch || path.length > bestMatch.length) {
                        bestMatch = path
                    }
                }
            }

            return bestMatch
        },

        lastFolderPart(path) {
            // Trim any trailing slash just in case
            path = path.replace(/\/$/, '')

            // Extract everything after the last slash
            const last = path.substring(path.lastIndexOf('/') + 1)

            // Return with leading slash
            return '/' + last
        },
        getSubfolders(active, list) {
            // normalize: remove trailing slash unless root
            const prefix = active === '/' ? '/' : active.replace(/\/$/, '') + '/'
            return list
                .filter((f) => f.startsWith(prefix) && f !== active) // only inside active
                .map((f) => f.slice(prefix.length)) // strip prefix
                .filter((f) => !f.includes('/')) // only direct children
                .map((f) => prefix + f) // reconstruct full path
        },

        slashCount(path) {
            return (path.match(/\//g) || []).length
        },

        getFolderWithCountSlashes(active_folder, folders, count) {
            const parent = this.truncatePath(active_folder, count)
            return folders.filter((path) => path.startsWith(parent) && (path.match(/\//g) || []).length === count)
        },

        truncatePath(path, count) {
            let pos = -1
            for (let i = 0; i < count; i++) {
                pos = path.indexOf('/', pos + 1)
                if (pos === -1) {
                    // less slashes than requested → return whole path
                    return path
                }
            }
            return path.substring(0, pos)
        },

        getPathUntilNSlash(path, n) {
            let pos = -1
            for (let i = 0; i < n; i++) {
                pos = path.indexOf('/', pos + 1)
                if (pos === -1) {
                    // fewer than n slashes → return whole path
                    return path
                }
            }
            let return_path = path.substring(0, pos)
            if (return_path == '') return_path = '/'
            return return_path
        },

        getParentFolder(path) {
            // Remove any trailing slash
            path = path.replace(/\/$/, '')

            // Find last slash
            const lastSlash = path.lastIndexOf('/')

            // If no parent, return root "/"
            if (lastSlash <= 0) return '/'

            return path.substring(0, lastSlash)
        },
    },
}
</script>
