<template>
    <div class="d-flex flex-column ga-1">
        <v-card class="mm-bg-folder mm-text-folder mm-border-folder" width="150" height="100"
            @click="$emit('onFolder')">
            <v-card-text class="text-caption d-flex flex-column align-center justify-center h-100 w-100">
                <div>{{ folder.name }}</div>
                <v-icon class="mm-icon-folder" icon="mdi-folder" size="large" />
                <div>{{ folder.folders_count }} Ord., {{ folder.files_count }} Dat.</div>
            </v-card-text>
        </v-card>
        <div class="d-flex flex-row flex-wrap align-center ga-1" v-if="!noActions">
            <!-- Download -->
            <v-btn size="small" :href="'/mediamanager/download_folder?folder=' + folder.path" target="_blank"><v-icon
                    icon="mdi-download" /></v-btn>
            <!-- Löschen -->
            <v-btn size="small" color="warning" v-if="delete_level == 0" @click="delete_level = 1"><v-icon
                    icon="mdi-delete" /></v-btn>
            <!-- Doch nicht löschen -->
            <v-btn size="small" color="success" v-if="delete_level == 1" @click="delete_level = 0"><v-icon
                    icon="mdi-delete-off" /></v-btn>
            <!-- Löschen durchführen -->
            <v-btn size="small" color="error" v-if="delete_level == 1" @click="destroyFolder"><v-icon
                    icon="mdi-delete" /></v-btn>
        </div>
    </div>
    

</template>


<script>
export default {
    emits: ['onFolder'],
    props: ['folder', 'noActions'],

    data() {
        return {
            delete_level: 0,

        };
    },

    methods: {
        destroyFolder() {
            this.delete_level = 0;
            this.$emit('onDestroyFolder');
        }

    }

}
</script>
