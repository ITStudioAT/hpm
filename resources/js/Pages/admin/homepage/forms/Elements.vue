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

                            <v-btn flat variant="tonal" color="primary" @click="editHeader"><v-icon
                                    icon="mdi-content-duplicate" />Ändern</v-btn>
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

            <!-- HEADER-->
            <v-expand-transition>

                <v-row class="w-100 mb-2" v-if="header && action === 'header'">
                    <!-- Spalte KOPFZEILE -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <Header :header="header" :colorItems="colorItems" :densityItems="densityItems"
                            :scrollBehaviorItems="scrollBehaviorItems" @clickAction="action = $event"
                            @confirmAction="(header) => confirmHeader(header)" />
                    </v-col>

                    <!-- Zeilen/Spalten - Aufbau der Kopfzeile -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <RowsAndColumns :header="header" :colorItems="colorItems" @clickAction="action = $event"
                            @confirmAction="(header) => confirmHeader(header)" />

                    </v-col>

                    <!-- SPALTEN -->
                    <!-- Spalten Zeile 1 -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <Row_1 :header="header" :justifyItems="justifyItems" :textVariantItems="textVariantItems"
                            @clickAction="action = $event" @confirmAction="(header) => confirmHeader(header)" />

                        <Row_2 :header="header" :justifyItems="justifyItems" :textVariantItems="textVariantItems"
                            @clickAction="action = $event" @confirmAction="(header) => confirmHeader(header)"
                            v-if="header.structure.rows.count > 1" />
                    </v-col>

                </v-row>

            </v-expand-transition>

            <v-row>
                ACTION:
                {{ action }}
            </v-row>


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

export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, Header, RowsAndColumns, Row_1, Row_2 },

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

            header: null,
            header_90: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["headers"]),

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

        },

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
