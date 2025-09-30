<template>
    <v-row no-gutters class="mb-2" v-if="active_folder">
        <v-card flat border :disabled="['new_folder', 'rename_folder'].includes(selected_action)">
            <v-card-title class="d-flex flex-row align-center justify-space-between bg-accent-2 mb-2">
                <div class="d-flex flex-row align-end">
                    <v-icon icon="mdi-map-marker" size="small" class="mr-2" />
                    <div class="text-body-2 font-weight-medium" :class="delete_count == 1 ? 'bg-error' : ''">
                        {{ active_folder }}
                    </div>
                </div>
                <div class="d-flex flex-row align-center ga-2" v-if="active_folder != '/'">
                    <v-btn icon="mdi-pencil" flat size="small" color="secondary" @click="renameFolder(active_folder)" />
                    <v-btn
                        icon="mdi-delete"
                        flat
                        size="small"
                        color="warning"
                        @click="delete_count = 1"
                        v-if="delete_count == 0" />
                    <v-btn
                        icon="mdi-delete-off"
                        flat
                        size="small"
                        color="success"
                        @click="delete_count = 0"
                        v-if="delete_count == 1" />
                    <v-btn
                        icon="mdi-delete"
                        flat
                        size="small"
                        color="error"
                        @click="doDestroy(homepage, folder_id, active_folder)"
                        v-if="delete_count == 1" />
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
                            <div v-for="(folder, i) in getFolderWithCountSlashes(folders, i)">
                                <its-folder
                                    :title="lastFolderPart(folder)"
                                    :is_path="startsWith(active_folder, folder)"
                                    :is_active="active_folder == folder"
                                    :delete_count="delete_count"
                                    @click="newActiveFolder(folder)"
                                    v-if="folder != '/'"
                                    class="mr-2 mb-2" />
                            </div>
                            <div>
                                <its-folder
                                    title="Neuer Ordner"
                                    icon="mdi-plus"
                                    @click="
                                        newFolder(
                                            getParentFolder(
                                                getMatchingPrefix(getFolderWithCountSlashes(folders, i), active_folder)
                                            )
                                        )
                                    "
                                    class="mr-2 mb-2"
                                    variant="outlined"
                                    color="secondary" />
                            </div>
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
                        <its-folder
                            title="Neuer Ordner"
                            icon="mdi-plus"
                            color="secondary"
                            variant="outlined"
                            @click="newFolder(active_folder)" />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-row>

    <!-- NEUE/EDIT FOLDER -->
    <NewEditFolder
        :data="data"
        :homepage="homepage"
        :path="path"
        :folder_id="folder_id"
        :type="type"
        v-if="['new_folder', 'rename_folder'].includes(selected_action)"
        @save="doSave($event)"
        @abort="doAbort" />
</template>
<script>
import { mapWritableState } from 'pinia'
import { useFolderStore } from '@/stores/admin/FolderStore'
import { usePageStore } from '@/stores/admin/PageStore'
import ItsFolder from '@/pages/components/ItsFolder.vue'
import NewEditFolder from './NewEditFolder.vue'

export default {
    emits: ['newActiveFolder'],
    props: ['homepage'],
    components: { ItsFolder, NewEditFolder },

    async beforeMount() {
        this.pageStore = usePageStore()
        this.folderStore = useFolderStore()

        await this.folderStore.index(this.homepage.id, 'page_folders')
    },

    unmounted() {},

    data() {
        return {
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
        ]),
        ...mapWritableState(usePageStore, ['active_page', 'pages']),
    },

    methods: {
        startsWith(path, item) {
            return path.startsWith(item) && (item.length === path.length || path.charAt(item.length) === '/')
        },
        async doDestroy(homepage, folder_id, path) {
            let answer = false
            answer = await this.folderStore.destroy(homepage, folder_id, path)
            await this.folderStore.index(homepage.id, this.type)

            this.active_page = null

            if (this.folders?.length) {
                this.active_folder = this.folders[0]
            }
            this.delete_count = 0
        },
        newActiveFolder(folder) {
            this.active_page = null
            this.active_folder = folder
            this.delete_count = 0
            this.$emit('newActiveFolder', folder)
        },
        renameFolder(folder) {
            this.delete_count = 0
            this.path = folder
            this.data.name = this.lastFolderPart(folder).replace(/^\/+/, '')
            this.selected_action = 'rename_folder'
        },
        newFolder(folder) {
            this.delete_count = 0
            this.path = folder
            this.data = {}
            this.selected_action = 'new_folder'
        },
        async doAbort() {
            await this.folderStore.index(this.homepage.id, this.type)
            this.data = {}
            this.selected_action = ''
        },
        async doSave(data) {
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

        getFolderWithCountSlashes(folders, count) {
            return folders.filter((path) => (path.match(/\//g) || []).length === count)
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
