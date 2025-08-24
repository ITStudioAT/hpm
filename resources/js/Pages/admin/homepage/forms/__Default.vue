<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100 mb-2" no-gutters>
            <v-col>
                <div class="bg-primary pa-2 text-h5">Kopfzeile</div>
            </v-col>
        </v-row>

        <v-form ref="form" @submit.prevent="$emit('save')">
            <!-- Menüleiste oben -->
            <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                <its-menu-button title="Abbruch" icon="mdi-close" color="warning" @click="abort" />
                <its-menu-button title="Speichern" type="submit" icon="mdi-content-save" color="success"
                    @click="save" />
            </v-row>

            <v-row class="w-100 mb-2" v-if="data">
                <!-- Einstellungen -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Einstellungen</v-card-title>
                        <v-card-text>
                            <v-text-field autofocus v-model="data.name" label="Name der Homepage" required />
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
import { STRUCTURE_DEFAULTS } from '@/constants/homepageDefaults'


export default {
    props: ["homepage"],
    components: { ItsMenuButton },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        const def = STRUCTURE_DEFAULTS;
        this.data = { ...this.homepage, structure: deepMergeDefaults(this.homepage.structure ?? {}, def) };

    },

    mounted() {

    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data"]),

    },

    watch: {
    },

    methods: {
        abort() { this.data = null; this.$emit("abort"); },
        async save() { await this.homepageStore.saveHomepage(this.data); this.$emit("save"); },

    },
};
</script>
