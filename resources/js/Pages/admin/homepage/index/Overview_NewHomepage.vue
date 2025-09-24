<template>

    <!-- NEUE HOMEPAGE -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-form ref="form" @submit.prevent="$emit('save', data)">
                <v-card flat tile color="primary">
                    <v-card-title class="d-flex flex-row align-center justify-space-between">
                        <div>
                            Neue Homepage
                        </div>
                        <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                    </v-card-title>
                    <v-card-text class="pt-4 ">
                        <v-text-field autofocus v-model="data.name" label="Name der Homepage" required />
                    </v-card-text>

                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" @click="$emit('save', data)">Speichern</v-btn>
                    </v-card-actions>

                </v-card>
            </v-form>
        </v-col>
    </v-row>
</template>
<script>


export default {

    props: ['data'],
    emits: ['save', 'abort'],

    components: {},

    async beforeMount() {

    },

    unmounted() {
    },

    data() {
        return {
        };
    },

    computed: {

    },

    methods: {


    }

}
</script>
