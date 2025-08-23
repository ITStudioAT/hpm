<template>
    <v-dialog persistent v-model="is_visible" max-width="1280" class="bg-primary">
        <v-container fluid class="ma-0 w-100 pa-2">
            <!-- MENÜ -->
            <v-row class="d-flex flex-row ga-2 mt-0 w-100 my-4" no-gutters>
                <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                    <its-menu-button color="mm-bg-error" title="Abbruch" icon="mdi-cancel" @click="abort" />

                    <its-menu-button color="mm-bg-success" title="Nehmen" icon="mdi-check" to="/hpm/admin/mm"
                        v-if="selected_files.length > 0" @click="takeIt" />


                </v-col>
            </v-row>

            <!-- FOLDER-UP -->
            <v-row no-gutters class="my-4" v-if="parent_folders.length > 0 && is_loading == 0">
                <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                    <FolderUp :parent="parent" v-for="(parent, i) in parent_folders"
                        :is_current="parent == current_folder" @click="onFolderUp(parent)" />
                </v-col>
            </v-row>


            <!-- FOLDERS -->
            <v-row no-gutters class="my-4" v-if="is_loading == 0">
                <v-col class="d-flex flex-row align-start ga-2 flex-wrap">

                    <Folder :folder="folder" v-for="(folder, i) in folders" @onFolder="onFolder(folder)"
                        @onDestroyFolder="onDestroyFolder(folder)" :noActions="true" />
                </v-col>
            </v-row>

            <v-row no-gutters class="my-4">
                <!-- FILE-LIST -->
                <ColBox :title="current_folder?.name" :subtitle="files.length + ' Dateien'"
                    color="var(--mm-bg-color-folder)" icon="mdi-file">
                    <div class="pt-1">
                        <v-list v-model:selected="selected_files" select-strategy="single-leaf"
                            :disabled="is_loading > 0" v-if="files.length > 0">
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
                            <PreviewItems :preview_files="preview_files" :noActions="true" selectStrategy="single" />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-dialog>

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
export default {

    setup() { return useValidationRulesSetup(); },

    components: { ItsMenuButton, ColBox, Folder, File, FolderUp, PreviewItems, FileUpload, NewFolder, Notification },

    emits: ['abort', 'takeIt'],

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
        this.parent_folders = [];
        this.selected_files = [];
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
            is_visible: true,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'current_folder', 'parent_folders', 'preview_files', 'is_loading_preview', 'is_loading', 'selected_files', 'notification']),
    },

    methods: {

        abort() {
            this.$emit('abort');
        },

        takeIt() {
            const data = { 'current_folder': this.current_folder.path, 'file': this.selected_files[0] }
            this.$emit('takeIt', data);
        },



        onFolder(folder) {
            this.parent_folders.push(folder);
            this.current_folder = folder;

            this.selected_files = [];
            this.preview_files = [];
            this.mediamanagerStore.folderStructure(folder?.path);
            this.mediamanagerStore.createPreview(folder?.path);
        },



        activate(element) {
            if (this.active_element == element) {
                this.active_element = null;
            } else {
                this.active_element = element;
            }

        },

    }

}
</script>