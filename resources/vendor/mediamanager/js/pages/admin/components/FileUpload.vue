<template>

    <v-card class="mm-text-folder mm-border-folder" width="300">
        <v-card-text>
            <file-pond class="w-100" name="file" ref="pond" label-idle="<strong>⬆️ Dateien oder Klick ⬆️</strong>"
                allowReplace chunkForce="true" allowRemove=false allowRevert="false" allow-multiple="true"
                labelFileProcessingComplete="Upload durchgeführt" :files="upload_files" :chunkUploads="true"
                :chunkForce="true" :chunkSize="5000000" :max-parallel-uploads="1" credits="false" :server="{
                    url: '/api/mediamanager/upload_filepond?path=' + (path ? path : ''),
                    method: 'POST',
                    timeout: 7000,
                    headers: { 'X-CSRF-TOKEN': csrf },
                    withCredentials: false,
                    files: upload_files,
                    revert: null,

                }" @processfiles="onUploadFinished" @processfileerror="onError" @processfileabort="onError"
                @error="onError" v-if="csrf" />

            <v-btn block flat color="primary" v-if="is_uploaded" @click="reset">Neustart</v-btn>
        </v-card-text>

    </v-card>

</template>


<script>
import { ref } from 'vue';

// Import Vue FilePond
import vueFilePond from "vue-filepond/dist/vue-filepond.js";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

export default {
    emits: ['fileUploadFinished'],

    props: ['path'],

    components: { FilePond },

    async beforeMount() {
        var response = await axios.get("/api/mediamanager/csrf", {});
        this.csrf = response.data;
    },

    data() {
        return {
            upload_files: [],
            csrf: null,
            is_uploaded: false,
        };
    },



    methods: {
        onUploadFinished() {
            this.is_uploaded = true;
            this.$emit('fileUploadFinished');
        },

        reset() {
            this.$refs.pond.removeFiles();
            this.is_uploaded = false;

        },

        onError() {
            this.is_uploaded = true;

        }

    }

}
</script>
