<template>

    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- MENÜ -->
        <v-row class="d-flex flex-row ga-2 mt-0 w-100 my-4" no-gutters>
            <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                <its-menu-button color="mm-bg-secondary" title="Auswahl" icon="mdi-image-size-select-actual"
                    @click="select" />
            </v-col>
        </v-row>

        <Select v-if="is_select" @abort="selectAbort" @takeIt="selectTakeIt" />



        <!-- FILEUPLOAD -->

        <v-row class="my-4" no-gutters v-if="is_loading == 0">
            <v-col>
                <FileUpload :path="current_folder?.path" @fileUploadFinished="onFileUploadFinished" />
            </v-col>
        </v-row>

        <!-- FOLDER-UP -->
        <v-row no-gutters class="my-4" v-if="parent_folders.length > 0 && is_loading == 0">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <FolderUp :parent="parent" v-for="(parent, i) in parent_folders" :is_current="parent == current_folder"
                    @click="onFolderUp(parent)" />
            </v-col>
        </v-row>

        <!-- FOLDERS -->
        <v-row no-gutters class="my-4" v-if="is_loading == 0">
            <v-col class="d-flex flex-row align-start ga-2 flex-wrap">
                <NewFolder @onFolder="createFolder" />

                <Folder :folder="folder" v-for="(folder, i) in folders" @onFolder="onFolder(folder)"
                    @onDestroyFolder="onDestroyFolder(folder)" />
            </v-col>
        </v-row>


        <!-- ACTION-BUTTONS -->
        <v-row no-gutters class="my-4" v-if="is_loading == 0">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <!-- Alle selektieren -->
                <v-btn color="secondary" v-if="selected_files.length < files.length" @click="onSelectAll"><v-icon
                        icon="mdi-select-all" /></v-btn>
                <!-- Alle abwählen -->
                <v-btn color="secondary" v-if="selected_files.length > 0" @click="onSelectOff"><v-icon
                        icon="mdi-select-off" /></v-btn>
                <!-- Umbenennen -->
                <v-btn color="secondary" v-if="selected_files.length == 1" @click="rename"><v-icon
                        icon="mdi-rename" /></v-btn>
                <!-- Löschen -->
                <v-btn color="warning" v-if="selected_files.length > 0 && delete_level == 0"
                    @click="delete_level = 1"><v-icon icon="mdi-delete" /></v-btn>
                <!-- Doch nicht löschen -->
                <v-btn color="success" v-if="selected_files.length > 0 && delete_level == 1"
                    @click="delete_level = 0"><v-icon icon="mdi-delete-off" /></v-btn>
                <!-- Löschen durchführen -->
                <v-btn color="error" v-if="selected_files.length > 0 && delete_level == 1" @click="destroyFiles"><v-icon
                        icon="mdi-delete" /></v-btn>

            </v-col>
        </v-row>


        <v-row no-gutters class="my-4">
            <!-- FILE-LIST -->
            <ColBox :title="current_folder?.name" :subtitle="files.length + ' Dateien'"
                color="var(--mm-bg-color-folder)" icon="mdi-file">
                <div class="pt-1">
                    <v-list v-model:selected="selected_files" select-strategy="classic" :disabled="is_loading > 0"
                        v-if="files.length > 0">
                        <v-list-item v-for="(file, i) in files" :value="file.name" :key="i">
                            <File :file="file" />
                        </v-list-item>
                    </v-list>
                    <div v-if="files.length == 0">
                        LEER
                    </div>
                </div>
            </ColBox>


            <!-- PREVIEW -->
            <v-col>
                <v-card tile flat :loading="is_loading_preview > 0">
                    <v-card-text class="d-flex flex-row align-center justify-center" style="min-height: 300px;"
                        v-if="is_loading_preview > 0">
                        <div class="text-h6">Vorbereiten der Vorschau...</div>
                    </v-card-text>
                    <v-card-text class="d-flex flex-row flex-wrap align-end ga-2">
                        <PreviewItems :preview_files="preview_files" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- RENAME FILE -->
        <v-dialog v-model="is_rename" persistent transition="dialog-bottom-transition" max-width="500">
            <v-card tile flat color="primary">
                <v-card-title>
                    Umbenennen
                </v-card-title>
                <v-card-text class="bg-secondary">
                    <v-form ref="form" v-model="is_valid" @submit.prevent="onSaveFilename(data)">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-text-field autofocus v-model="data.filename" label="Dateiname"
                                    :rules="[required(), maxLength(255)]" />
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mt-4">
                            <v-col cols="6">
                                <v-btn block flat tile text="Abbruch" color="error" prepend-icon="mdi-close"
                                    @click="is_rename = false" />
                            </v-col>
                            <v-col cols="6">
                                <v-btn block flat tile text="Speichern" color="success" prepend-icon="mdi-content-save"
                                    @click="onSaveFilename(data)" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!-- CREATE FOLDER -->
        <v-dialog v-model="is_create_folder" persistent transition="dialog-bottom-transition" max-width="500">
            <v-card tile flat color="primary">
                <v-card-title>
                    Neuer Ordner
                </v-card-title>
                <v-card-text class="bg-secondary">
                    <v-form ref="form_folder" v-model="is_valid" @submit.prevent="onSaveFolder(data)">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-text-field autofocus v-model="data.name" label="Name"
                                    :rules="[required(), maxLength(255)]" />
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mt-4">
                            <v-col cols="6">
                                <v-btn block flat tile text="Abbruch" color="error" prepend-icon="mdi-close"
                                    @click="is_create_folder = false" />
                            </v-col>
                            <v-col cols="6">
                                <v-btn block flat tile text="Speichern" color="success" prepend-icon="mdi-content-save"
                                    @click="onSaveFolder(data)" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>

        <Notification :notification="notification" />

    </v-container>

</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import { mapWritableState } from "pinia";
import { useMediamanagerStore } from "../../../stores/MediamanagerStore";
import ItsMenuButton from "../components/ItsMenuButton.vue";
import ColBox from "../components/ColBox.vue";
import Folder from "../components/Folder.vue";
import NewFolder from "../components/NewFolder.vue";
import FolderUp from "../components/FolderUp.vue";
import File from "../components/File.vue";
import PreviewItems from "../components/PreviewItems.vue";
import FileUpload from "../components/FileUpload.vue";
import Notification from "../components/Notification.vue";
import Select from "../select/Select.vue";
export default {

    setup() { return useValidationRulesSetup(); },

    components: { ItsMenuButton, ColBox, Folder, File, FolderUp, PreviewItems, FileUpload, NewFolder, Notification, Select },

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
        await this.mediamanagerStore.folderStructure();
        this.mediamanagerStore.createPreview();

    },


    unmounted() {
    },

    data() {
        return {
            mediamanagereStore: null,
            active_element: 'home',
            delete_level: 0,
            is_rename: false,
            is_create_folder: false,
            data: {},
            is_valid: false,
            is_select: true,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'current_folder', 'parent_folders', 'preview_files', 'is_loading_preview', 'is_loading', 'selected_files', 'notification']),
    },

    methods: {

        select() {
            this.is_select = true;

        },
        selectAbort() {
            this.is_select = false;
        },

        selectTakeIt(data) {
            console.log(data);
            this.is_select = false;
        },

        rename() {
            const fullName = this.selected_files[0];
            const dotIndex = fullName.lastIndexOf(".");
            const nameWithoutExt = fullName.substring(0, dotIndex);
            const extension = fullName.substring(dotIndex + 1);
            this.data = {
                path: this.current_folder ? this.current_folder.path : '',
                current_filename: fullName,
                filename: nameWithoutExt,
                extension: extension,
            }
            this.is_rename = true;
        },

        async onSaveFilename(data) {
            this.is_valid = false; await this.$refs.form.validate(); if (!this.is_valid) return;
            this.is_rename = false;
            await this.mediamanagerStore.saveFilename(data);
            this.selected_files = [];
            this.preview_files = [];
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
        },

        async destroyFiles() {
            this.delete_level = 0;
            this.mediamanagerStore.destroyFiles(this.selected_files, this.current_folder?.path);
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
            this.selected_files = [];
        },

        async onDestroyFolder(folder) {
            this.selected_files = [];
            this.preview_files = [];
            this.mediamanagerStore.destroyFolder(folder.path);
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);

        },

        onFileUploadFinished(response) {
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
            this.selected_files = [];
        },

        goError() {
            console.log("goError");
            this.$emit('error');
        },

        onSelectOff() {
            this.selected_files = [];
        },

        onSelectAll() {
            this.selected_files = this.files.map(file => file.name);
        },

        onFolderUp(parent) {

            this.selected_files = [];
            this.preview_files = [];
            const found = this.parent_folders.find((item) => item.path == parent.path);
            const index = this.parent_folders.findIndex(item => item.path === parent.path);
            if (index !== -1) {
                this.parent_folders.splice(index);
            }

            if (this.parent_folders.length >= 1) { this.current_folder = this.parent_folders[this.parent_folders.length - 1]; } else { this.current_folder = null; }

            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
        },

        onFolder(folder) {
            this.parent_folders.push(folder);
            this.current_folder = folder;

            this.selected_files = [];
            this.preview_files = [];
            this.mediamanagerStore.folderStructure(folder?.path);
            this.mediamanagerStore.createPreview(folder?.path);
        },


        createFolder() {
            this.data = {};
            this.is_create_folder = true;
        },


        async onSaveFolder(data) {
            this.is_valid = false; await this.$refs.form_folder.validate(); if (!this.is_valid) return;
            this.is_create_folder = false;

            this.selected_files = [];
            this.preview_files = [];
            const path = this.current_folder?.path ? this.current_folder?.path : '';
            this.mediamanagerStore.createFolder(path, data.name);
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
        },

        activate(element) {
            console.log(element);
            if (this.active_element == element) {
                this.active_element = null;
            } else {
                this.active_element = element;
            }

        },

    }

}
</script>