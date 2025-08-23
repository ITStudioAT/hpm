<template>
    <div class="d-flex flex-column ga-1">
        <v-card class="mm-bg-folder mm-text-folder mm-border-folder" width="150" height="100"
            @click="$emit('onFolder')">
            <v-card-text class="text-caption d-flex flex-column align-center justify-center h-100 w-100">
                <div>Neuer</div>
                <v-icon class="mm-icon-folder" icon="mdi-folder-plus" size="large" color="success" />
                <div>Ordner</div>

            </v-card-text>
        </v-card>

    </div>

</template>


<script>
export default {
    emits: ['onFolder'],
    props: [],

    data() {
        return {

        };
    },

    methods: {
    }

}
</script>
