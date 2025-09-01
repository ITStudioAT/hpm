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
                        <RowsAndColumns :header="header" @clickAction="action = $event"
                            @confirmAction="(header) => confirmHeader(header)" />

                    </v-col>

                    <!-- SPALTEN -->
                    <!-- Spalten Zeile 1 -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <Row_1 :header="header" :justifyItems="justifyItems" :textVariantItems="textVariantItems"
                            @clickAction="action = $event" @confirmAction="(header) => confirmHeader(header)" />

                    </v-col>

                </v-row>

            </v-expand-transition>



            <v-row>
                {{ header.structure.rows.row_1.desktop }}
            </v-row>
            <v-row class="mt-12">
                {{ header.structure.rows.row_1.tablet }}
            </v-row>
            <v-row class="mt-12">
                {{ header.structure.rows.row_1.handy }}
            </v-row>


            <!-- VORSCHAU -->
            <v-row>
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
import HeaderAndFooter from "./LandingPage/HeaderAndFooter.vue";
import Header from "./LandingPage/Header.vue";
import RowsAndColumns from "./LandingPage/RowsAndColumns.vue";
import Row_1 from "./LandingPage/Row_1.vue";

// <-- NEU: generierte Defaults/Schemas verwenden
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";


export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, HeaderAndFooter, Header, RowsAndColumns, Row_1 },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        // 1) Index-Record laden
        await this.homepageStore.loadRecord(this.homepage.id, this.homepage.structure.index.id);

        // Defaults mergen (Index)
        const indexDef = cloneStructure("index");
        const indexMerged = deepMergeDefaults(this.record.structure ?? {}, indexDef);
        // Optional validieren (ganzer Index-Baum)
        try { HPM_SCHEMAS.index.parse(indexMerged); } catch (e) { console.warn("Invalid index structure:", e); }

        this.index = { ...this.record, structure: indexMerged };
        this.index_90 = { ...this.record, structure: deepMergeDefaults(this.record.structure ?? {}, indexDef) };

        // 2) Header-Record laden
        await this.homepageStore.loadRecord(this.homepage.id, this.index.structure.header.id);
        const headerDef = cloneStructure("header");
        const headerMerged = deepMergeDefaults(this.record.structure ?? {}, headerDef);
        try { HPM_SCHEMAS.header.parse(headerMerged); } catch (e) { console.warn("Invalid header structure:", e); }
        this.header = { ...this.record, structure: headerMerged };
        this.header_90 = { ...this.record, structure: deepMergeDefaults(this.record.structure ?? {}, headerDef) };

        // 3) Footer-Record laden
        await this.homepageStore.loadRecord(this.homepage.id, this.index.structure.footer.id);
        const footerDef = cloneStructure("footer");
        const footerMerged = deepMergeDefaults(this.record.structure ?? {}, footerDef);
        try { HPM_SCHEMAS.footer.parse(footerMerged); } catch (e) { console.warn("Invalid footer structure:", e); }
        this.footer = { ...this.record, structure: footerMerged };
        this.footer_90 = { ...this.record, structure: deepMergeDefaults(this.record.structure ?? {}, footerDef) };


        // Preview
        this.preview_src =
            "/homepage/example/header_and_footer?homepage_id=" +
            this.index.homepage_id + "&record_id=" + this.index.id;

        this.is_ready = true;
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

            // ACHTUNG: 'transparent' ist NICHT in deinem Schema-Enum.
            // Wenn du transparent brauchst, erweitere das Generator-Override (color-Enum) um 'transparent'.
            colorItems: [
                { label: 'Farbe A', value: 'first' },
                { label: 'Farbe B', value: 'second' },
                { label: 'Farbe C', value: 'third' },
                { label: 'Transparent', value: 'transparent' },
            ],
            densityItems: [
                { label: "Standard", value: "default" },
                { label: "Prominent (hoch)", value: "prominent" },
                { label: "Komfortabel", value: "comfortable" },
                { label: "Kompakt (niedrig)", value: "compact" }
            ],

            scrollBehaviorItems: [
                { label: "Verschwindet", value: "hide" },
                { label: "Standard", value: "default" },
                { label: "Erhöhen beim Scrollen", value: "elevate" }
            ],
            justifyItems: [
                { label: "linksbündig", value: "justify-start" },
                { label: "mittig", value: "justify-center" },
                { label: "rechtsbündig", value: "justify-end" },

            ],
            textVariantItems: [
                { label: "Hero", value: "heroLead" },
                { label: "Titel", value: "title" },
                { label: "Untertitel", value: "subtitle" },
                { label: "Inhalt", value: "content" },
                { label: "Anmerkung", value: "subcontent" },

            ],

            is_valid: false,
            is_select: '',
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data", "record"])
    },

    methods: {
        async confirmHeader(header) {
            this.header = header;
            await this.confirm('header');
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
