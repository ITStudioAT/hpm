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

            <!-- Header and Footer-->
            <HeaderAndFooter :index="index" @update="update" @clickAction="action = $event"
                v-if="index && action === ''" />


            <!-- HEADER-->
            <v-expand-transition>

                <v-row class="w-100 mb-2" v-if="index && action === 'header'">
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
                <v-col>
                    <its-menu-button title="Vorschau" subtitle="Neues Fenster" icon="mdi-eye" color="primary"
                        @click="openPreview" v-if="is_preview_intern" />

                    <its-menu-button title="Vorschau" subtitle="schließen" icon="mdi-close" color="warning"
                        @click="closePreview" v-if="!is_preview_intern" />

                </v-col>

            </v-row>


            <!-- VORSCHAU -->
            <v-row v-if="is_preview_intern">
                <v-col cols="12" md="8">
                    <div class="mb-2 font-weight-medium">Desktop</div>
                    <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                        style="width:1200px; height:600px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                        allow="fullscreen; clipboard-read; clipboard-write" />
                </v-col>

                <v-col cols="12" md="4">
                    <div class="mb-2 font-weight-medium">Handy</div>
                    <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                        style="width:390px; height:600px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                        allow="fullscreen; clipboard-read; clipboard-write" />
                </v-col>
            </v-row>
            <v-row v-if="is_preview_intern">
                <v-col cols="12" md="8">
                    <div class="mb-2 font-weight-medium">Tablet</div>
                    <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                        style="width:960px; height:500px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                        allow="fullscreen; clipboard-read; clipboard-write" />
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import { z } from "zod";
import { useValidationRulesSetup } from "@/helpers/rules";
import { mapWritableState } from "pinia";
import { deepMergeDefaults } from "@/helpers/merge";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import HeaderAndFooter from "./LandingPage/HeaderAndFooter.vue";
import Header from "./LandingPage/Header.vue";
import RowsAndColumns from "./LandingPage/RowsAndColumns.vue";
import Row_1 from "./LandingPage/Row_1.vue";
import Row_2 from "./LandingPage/Row_2.vue";

// <-- NEU: generierte Defaults/Schemas verwenden
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";
import { COLOR_ITEMS, DENSITY_ITEMS, SCROLL_BEHAVIOR_ITEMS, JUSTIFY_ITEMS, TEXT_VARIANT_ITEMS } from "@/constants/uiOptions";


export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, HeaderAndFooter, Header, RowsAndColumns, Row_1, Row_2 },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.adminStore.initialize(this.$router)
        this.homepageStore = useHomepageStore()

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

        // ---------- 2) HEADER ----------
        await this.homepageStore.loadRecord(this.homepage.id, this.index.structure.header.id)
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

        // ---------- 3) FOOTER ----------
        await this.homepageStore.loadRecord(this.homepage.id, this.index.structure.footer.id)
        const footerRecord = { ...this.record } // Snapshot

        const footerDef = cloneStructure("footer")
        const footerMerged = deepMergeDefaults(footerRecord.structure ?? {}, footerDef)

        const footerSchemaStripping =
    /** @type {import('zod').ZodTypeAny} */ (HPM_SCHEMAS.footer).strip()

        let footerClean
        try {
            footerClean = footerSchemaStripping.parse(footerMerged)
        } catch (e) {
            console.warn("Invalid footer structure (after strip):", e)
            footerClean = footerDef
        }

        this.footer = { ...footerRecord, structure: footerClean }
        this.footer_90 = { ...footerRecord, structure: footerClean }

        // ---------- Preview ----------
        this.preview_src =
            `/homepage/example/header_and_footer?homepage_id=${this.index.homepage_id}&record_id=${this.index.id}`

        this.is_ready = true
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            is_ready: false,
            reloadKey: 0,
            preview_src: null,
            action: "",
            index: null,
            index_90: null,
            header: null,
            header_90: null,
            footer: null,
            footer_90: null,

            colorItems: COLOR_ITEMS,
            densityItems: DENSITY_ITEMS,
            scrollBehaviorItems: SCROLL_BEHAVIOR_ITEMS,
            justifyItems: JUSTIFY_ITEMS,
            textVariantItems: TEXT_VARIANT_ITEMS,

            is_valid: false,
            is_select: '',
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
            blank_window: null,
            is_preview_intern: true,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data", "record"])
    },

    methods: {
        openPreview() {

            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.close();
            }

            const src =
                "/admin/homepage/landing_page_preview?id=" +
                this.index.homepage_id;

            this.blank_window = window.open(src, "_blank");
            this.is_preview_intern = false;

        },

        closePreview() {

            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.close();
            }

            this.is_preview_intern = true;

        },

        async confirmHeader(header) {
            this.header = header;
            await this.confirm('header');
            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.location.reload();
            }
        },
        async abort() {
            await this.homepageStore.saveRecord(this.index_90);
            await this.homepageStore.saveRecord(this.header_90);
            await this.homepageStore.saveRecord(this.footer_90);
            this.$emit("abort");
        },

        async save() {
            HPM_SCHEMAS.index.parse(this.index.structure);
            HPM_SCHEMAS.header.parse(this.header.structure);   // erlaubt jetzt null bei height/elevation
            HPM_SCHEMAS.footer.parse(this.footer.structure);
            await this.homepageStore.saveRecord(this.index);
            await this.homepageStore.saveRecord(this.header);
            await this.homepageStore.saveRecord(this.footer);
            this.$emit("save");
        },

        // wurde vom Checkbox-Update für Sichtbarkeit aufgerufen → speichere den Index-Record
        async update() {
            HPM_SCHEMAS.index.parse(this.index.structure);
            await this.homepageStore.saveRecord(this.index);
            this.reloadKey++;
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

    }
};
</script>
