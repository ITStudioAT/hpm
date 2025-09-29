<template>
    <!-- NEUE HOMEPAGE -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-form ref="form" @submit.prevent="doSave(homepage, path, data)" v-model="is_valid">
                <v-card flat tile color="primary">
                    <v-card-title class="d-flex flex-row align-center justify-space-between">
                        <div v-if="!data">Neuer Ordner</div>
                        <div v-if="data">Ordner umbenennen</div>
                        <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                    </v-card-title>
                    <v-card-subtitle>in: {{ path }}</v-card-subtitle>
                    <v-card-text class="pt-4">
                        <v-text-field
                            autofocus
                            v-model="data.name"
                            label="Name des Ordners"
                            :rules="[required(), maxLength(255)]" />
                    </v-card-text>

                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" type="submit">Speichern</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-col>
    </v-row>
</template>
<script>
import { mapWritableState } from 'pinia'
import { useValidationRulesSetup } from '@/helpers/rules'
import { useFolderStore } from '@/stores/admin/FolderStore'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['data', 'homepage', 'path', 'folder_id', 'type'],
    emits: ['save', 'abort'],

    components: {},

    async beforeMount() {
        this.folderStore = useFolderStore()
        this.data_90 = JSON.parse(JSON.stringify(this.data))
    },

    unmounted() {},

    data() {
        return {
            folderStore: null,
            is_valid: false,
            data_90: null,
        }
    },

    computed: {
        ...mapWritableState(useFolderStore, ['active_folder']),
    },

    methods: {
        async doSave(homepage, path, data) {
            if (Object.keys(this.data_90).length === 0) {
                await this.doStore(homepage, this.folder_id, path, data)
            } else {
                await this.doUpdate(homepage, this.folder_id, path, data)
            }
        },
        async doStore(homepage, folder_id, path, data) {
            this.is_valid = false
            await this.$refs.form.validate()
            if (!this.is_valid) return
            let answer = false
            answer = await this.folderStore.store(homepage, folder_id, path, data)

            if (!answer) return

            await this.folderStore.index(homepage.id, this.type)
            this.$emit('save')
        },

        async doUpdate(homepage, folder_id, path, data) {
            this.is_valid = false
            await this.$refs.form.validate()
            if (!this.is_valid) return
            let answer = false
            answer = await this.folderStore.update(homepage, folder_id, path, data)
            if (!answer) return

            this.active_folder = this.renameActiveFolder(this.active_folder, data.name)

            await this.folderStore.index(homepage.id, this.type)
            this.$emit('save')
        },

        renameActiveFolder(activeFolder, newName) {
            // remove trailing slash if present
            activeFolder = activeFolder.replace(/\/$/, '')

            // split into parts
            const parts = activeFolder.split('/')

            // replace the last segment with the new name
            parts[parts.length - 1] = newName

            // join back
            return parts.join('/')
        },
    },
}
</script>
