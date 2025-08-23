<template>

    <v-card class="mm-text-folder mm-border-folder" width="300">
        <v-card-text>
            <file-pond name="file" ref="pond" allow-multiple :chunkUploads="true" :chunkSize="5000000" :server="{
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    const formData = new FormData();
                    formData.append(fieldName, file);

                    const request = new XMLHttpRequest();
                    request.open('POST', `/api/mediamanager/upload?path=${encodeURIComponent(this.path)}&filename=${encodeURIComponent(file.name)}`);

                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };

                    request.onload = function () {
                        if (request.status >= 200 && request.status < 300) {
                            load(request.responseText);
                        } else {
                            error('Upload failed');
                        }
                    };

                    request.send(formData);

                    return {
                        abort: () => {
                            request.abort();
                            abort();
                        }
                    };
                }
            }" />

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
