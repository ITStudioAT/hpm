<template>
    <v-container fluid class="ma-0 w-100 pa-2" v-if="is_ready">
        <v-row class="w-100 mb-2" no-gutters>
            <v-col>
                <div class="bg-primary pa-2 text-h5">Startseite</div>
            </v-col>
        </v-row>


        <v-form ref="form" @submit.prevent="$emit('save')">

            <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                <its-menu-button title="Abbruch" icon="mdi-close" color="warning" @click="abort" />
                <its-menu-button title="Speichern" type="submit" icon="mdi-content-save" color="success"
                    @click="save" />
            </v-row>

            <v-row class="w-100 mb-2" v-if="data">

                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Kopf- und Fußzeile</v-card-title>
                        <v-card-text>
                            <div class="d-flex flex-row align-center justify-space-between">
                                <v-checkbox v-model="data.structure.header.is_visible" color="success" label="Kopfzeile"
                                    hide-details></v-checkbox>
                                <v-btn flat color="primary"
                                    :disabled="!data.structure.header.is_visible">Bearbeiten</v-btn>
                            </div>
                            <div class="d-flex flex-row align-center justify-space-between">
                                <v-checkbox v-model="data.structure.footer.is_visible" color="success" label="Fußzeile"
                                    hide-details></v-checkbox>
                                <v-btn flat color="primary"
                                    :disabled="!data.structure.footer.is_visible">Bearbeiten</v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>

            </v-row>



        </v-form>


    </v-container>
</template>

<script>
import { h, markRaw } from "vue";
import { mapWritableState } from "pinia";
import { deepMergeDefaults } from "@/helpers/merge";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import { STRUCTURE_DEFAULTS } from '@/constants/indexDefaults'


export default {
    props: ["homepage"],
    components: { ItsMenuButton },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        console.log(this.homepage.id, this.homepage.structure.index.id);

        await this.homepageStore.loadRecord(this.homepage.id, this.homepage.structure.index.id);

        const def = STRUCTURE_DEFAULTS;
        this.data = { ...this.record, structure: deepMergeDefaults(this.record.structure ?? {}, def) };

        this.is_ready = true;

    },

    mounted() {

    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            is_ready: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data", "record"]),

    },

    watch: {
    },

    methods: {
        abort() { this.data = null; this.$emit("abort"); },
        async save() { await this.homepageStore.saveRecord(this.data); this.$emit("save"); },

    },
};
</script>
