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
                <v-col cols="12" md="6" lg="4" xl="3" v-if="action == ''">

                    <v-card>
                        <!-- ÜBERSICHT -->
                        <!-- selectedHeader && action_2 == ''-->
                        <v-card-text class="d-flex flex-row flex-wrap align-center ga-4"
                            v-if="selectedHeader && action_2 == ''">

                            <v-btn flat variant="tonal" color="primary" @click="copyHeader"><v-icon
                                    icon="mdi-content-duplicate" />Kopieren</v-btn>

                            <v-btn flat variant="tonal" color="primary" @click="rename"><v-icon
                                    icon="mdi-rename" />Umbenennen</v-btn>

                            <v-btn flat variant="tonal" color="warning" @click="action_2 = 'delete'"><v-icon
                                    icon="mdi-delete" />Löschen</v-btn>

                            <v-btn flat variant="tonal" color="primary" @click="editHeader"><v-icon
                                    icon="mdi-content-duplicate" />Bearbeiten</v-btn>

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
                <v-col cols="12" md="6" lg="4" xl="3" v-if="action == ''">
                    <v-card>
                        <v-card-title>Fußzeilen</v-card-title>
                        <v-card-text>
                            {{ selectedHeader }}
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Menüs -->
                <v-col cols="12" md="6" lg="4" xl="3" v-if="action == ''">
                    <v-card>
                        <v-card-title>Menüs</v-card-title>
                        <v-card-text>
                            {{ data }}
                        </v-card-text>
                    </v-card>
                </v-col>

            </v-row>

            <!-- HEADER-->
            <EditHeader :index="index" :header="header" :reloadKey="reloadKey" @confirmHeader="confirmHeader"
                @abort="abort" v-if="index && action === 'header'" />

            <!-- Preview -->
            <Preview :index="index" :reloadKey="reloadKey" v-if="index && action === 'header'" />






        </v-form>
    </v-container>
</template>



<script>


import { useValidationRulesSetup } from "@/helpers/rules";
import { mapWritableState } from "pinia";
import { deepMergeDefaults } from "@/helpers/merge";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";

import Header from "./LandingPage/Header.vue";
import RowsAndColumns from "./LandingPage/RowsAndColumns.vue";
import Row_1 from "./LandingPage/Row_1.vue";
import Row_2 from "./LandingPage/Row_2.vue";
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";
import { COLOR_ITEMS, DENSITY_ITEMS, SCROLL_BEHAVIOR_ITEMS, JUSTIFY_ITEMS, TEXT_VARIANT_ITEMS } from "@/constants/uiOptions";

import EditHeader from "./LandingPage/EditHeader.vue";
import Preview from "./LandingPage/Preview.vue";

export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, Header, RowsAndColumns, Row_1, Row_2, EditHeader, Preview },

    async beforeMount() {


        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        await this.homepageStore.loadHeaders(this.homepage.id);

        // ---------- 1) INDEX ----------
        await this.homepageStore.loadRecord(this.homepage.id, this.homepage.structure.index.id)
        const indexRecord = { ...this.record } // Snapshot

        const indexDef = cloneStructure("index")
        const indexMerged = deepMergeDefaults(indexRecord.structure ?? {}, indexDef)

        // Strip entfernt unbekannte Keys
        const indexSchemaStripping =
    /** @type {import('zod').ZodTypeAny} */ (HPM_SCHEMAS.index).strip()

        let indexClean
        try {
            indexClean = indexSchemaStripping.parse(indexMerged)
        } catch (e) {
            console.warn("Invalid index structure (after strip):", e)
            indexClean = indexDef
        }

        this.index = { ...indexRecord, structure: indexClean }
        this.index_90 = { ...indexRecord, structure: indexClean }


        this.is_ready = true
    },

    mounted() {

    },

    data() {
        return {
            is_show: false,
            adminStore: null,
            homepageStore: null,

            selectedHeader: null,
            action: '',
            action_2: '',
            data: null,
            is_valid: false,

            colorItems: COLOR_ITEMS,
            densityItems: DENSITY_ITEMS,
            scrollBehaviorItems: SCROLL_BEHAVIOR_ITEMS,
            justifyItems: JUSTIFY_ITEMS,
            textVariantItems: TEXT_VARIANT_ITEMS,

            index: null,
            index_90: null,
            header: null,
            header_90: null,
            footer: null,
            footer_90: null,

            reloadKey: 0,
            is_ready: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["headers", "record"]),
    },

    watch: {
    },

    methods: {


        async editHeader() {
            // ---------- 2) HEADER ----------
            await this.homepageStore.loadRecord(this.homepage.id, this.selectedHeader.id)
            const headerRecord = { ...this.record } // Snapshot

            const headerDef = cloneStructure("header")
            const headerMerged = deepMergeDefaults(headerRecord.structure ?? {}, headerDef)

            const headerSchemaStripping = (HPM_SCHEMAS.header).strip()

            let headerClean
            try {
                headerClean = headerSchemaStripping.parse(headerMerged)
            } catch (e) {
                console.warn("Invalid header structure (after strip):", e)
                headerClean = headerDef
            }

            this.header = { ...headerRecord, structure: headerClean }
            this.header_90 = { ...headerRecord, structure: headerClean }

            this.action = 'header';
            this.action_2 = 'edit_header';

        },

        async abort() {
            await this.homepageStore.saveRecord(this.index_90);
            await this.homepageStore.saveRecord(this.header_90);
            this.action = '';
            this.action_2 = '';
            this.selectedHeader = null;

        },

        async copyHeader() {
            if (!this.selectedHeader) return;
            await this.homepageStore.copyRecord(this.selectedHeader.id);
            await this.homepageStore.loadHeaders(this.homepage.id);
            this.selectedHeader = null;
        },

        rename() {
            this.data = JSON.parse(JSON.stringify(this.selectedHeader));
            this.action_2 = 'rename';

        },

        async deleteRecord(id) {
            await this.homepageStore.deleteRecord(id);
            await this.homepageStore.loadHeaders(this.homepage.id);
            this.action_2 = "";
            this.selectedHeader = null;
        },

        async saveRecord() {
            await this.homepageStore.saveRecord(this.data);
            await this.homepageStore.loadHeaders(this.homepage.id);
            this.action_2 = "";
            this.selectedHeader = null;
        },



        async confirmHeader(header) {
            this.header = header;
            await this.confirm('header');
            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.location.reload();
            }

        },
        async confirm(kind) {
            if (kind === 'header') {
                HPM_SCHEMAS.header.parse(this.header.structure);
                await this.homepageStore.saveRecord(this.header);
            } else if (kind === 'footer') {
                HPM_SCHEMAS.footer.parse(this.footer.structure);
                await this.homepageStore.saveRecord(this.footer);
            } else if (kind === 'index') {
                HPM_SCHEMAS.index.parse(this.index.structure);
                await this.homepageStore.saveRecord(this.index);
            }
            this.reloadKey++;
        },



    },
};
</script>
