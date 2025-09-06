<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100 mb-2" no-gutters>
            <v-col>
                <div class="bg-primary pa-2 text-h5">Elemente</div>
            </v-col>
        </v-row>

        <v-form ref="form">


            <v-row class="w-100 mb-2">
                <!-- Kopfzeilen -->
                <v-col cols="12" md="6" lg="4" xl="3">

                    <v-card>
                        <!-- ÜBERSICHT -->
                        <v-card-text class="d-flex flex-row flex-wrap align-center ga-4"
                            v-if="selectedHeader && action_2 == ''">
                            <v-btn flat variant="tonal" color="primary" @click="copyHeader"><v-icon
                                    icon="mdi-content-duplicate" />Kopieren</v-btn>

                            <v-btn flat variant="tonal" color="primary" @click="rename"><v-icon
                                    icon="mdi-rename" />Umbenennen</v-btn>

                            <v-btn flat variant="tonal" color="warning" @click="action_2 = 'delete'"><v-icon
                                    icon="mdi-delete" />Löschen</v-btn>
                        </v-card-text>
                        <v-card-title>Kopfzeilen</v-card-title>
                        <v-card-text :class="action_2 != '' ? 'd-none' : ''">
                            <v-list :items="headers" item-title="name" item-value="id" select-strategy="single-leaf"
                                return-object @update:selected="val => selectedHeader = val[0]" />
                        </v-card-text>

                        <!-- DELETE -->
                        <v-card-text v-if="action_2 == 'delete'">
                            <!-- overview: action_2 == 'delete': Löschen -->
                            <v-row>
                                <v-col>
                                    <div class="text-body-1 font-weight-bold text-error">{{ selectedHeader.name }}
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="6">
                                    <v-btn block prepend-icon="mdi-delete-off" @click="action_2 = ''" variant="tonal"
                                        color="primary">Abbruch</v-btn>
                                </v-col>
                                <v-col cols="6">
                                    <v-btn block prepend-icon="mdi-delete" @click="deleteRecord(selectedHeader.id)"
                                        variant="tonal" color="error">Löschen</v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <!-- RENAME -->
                        <v-card-text v-if="action_2 == 'rename'">
                            <!-- overview: action_2 == 'rename': Umbenennen -->

                            <v-form ref="form" v-model="is_valid" @submit.prevent="saveRecord"
                                @keydown.esc.prevent="action_2 = ''">
                                <v-row v-if="action_2 === 'rename'">
                                    <v-col cols="12">
                                        <v-text-field autofocus flat rounded="0" v-model="data.name"
                                            label="Name der Homepage" :rules="[required(), maxLength(255)]" />
                                    </v-col>
                                    <v-col cols="6">
                                        <v-btn block prepend-icon="mdi-close" @click="action_2 = ''" variant="tonal"
                                            color="primary">Abbruch</v-btn>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-btn block prepend-icon="mdi-content-save" @click="saveRecord" variant="tonal"
                                            color="success">Umbenennen</v-btn>
                                    </v-col>

                                </v-row>
                            </v-form>

                        </v-card-text>
                    </v-card>






                </v-col>

                <!-- Fußzeilen -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Fußzeilen</v-card-title>
                        <v-card-text>
                            {{ selectedHeader }}
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Menüs -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Menüs</v-card-title>
                        <v-card-text>
                            {{ data }}
                        </v-card-text>
                    </v-card>
                </v-col>

            </v-row>


        </v-form>
    </v-container>
</template>



<script>
import { useValidationRulesSetup } from "@/helpers/rules";
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";

export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        await this.homepageStore.loadHeaders(this.homepage.id);





    },

    mounted() {

    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            selected_header: null,
            selectedHeader: null,
            action_2: '',
            data: null,
            is_valid: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["headers"]),

    },

    watch: {
    },

    methods: {
        abort() { this.data = null; this.$emit("abort"); },

        async copyHeader() {
            if (!this.selectedHeader) return;
            await this.homepageStore.copyRecord(this.selectedHeader.id);
            await this.homepageStore.loadHeaders(this.homepage.id);
        },

        rename() {
            this.data = JSON.parse(JSON.stringify(this.selectedHeader));
            this.action_2 = 'rename';

        },

        async deleteRecord(id) {
            await this.homepageStore.deleteRecord(id);
            await this.homepageStore.loadHeaders(this.homepage.id);
            this.action_2 = "";
        },

        async saveRecord() {
            await this.homepageStore.saveRecord(this.data);
            await this.homepageStore.loadHeaders(this.homepage.id);
            this.action_2 = "";
        },


    },
};
</script>
