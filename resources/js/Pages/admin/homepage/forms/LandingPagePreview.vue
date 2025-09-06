<template>
    <v-container fluid class="ma-0 w-100 pa-2" v-if="is_ready">
        <!-- VORSCHAU -->
        <v-row>
            <v-col>
                <its-menu-button title="Reload" icon="mdi-reload" color="primary" @click="reload" />
            </v-col>
        </v-row>
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
        <v-row>
            <v-col cols="12" md="8">
                <div class="mb-2 font-weight-medium">Tablet</div>
                <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                    style="width:960px; height:500px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                    allow="fullscreen; clipboard-read; clipboard-write" />
            </v-col>
        </v-row>

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
import Row_2 from "./LandingPage/Row_2.vue";
// <-- NEU: generierte Defaults/Schemas verwenden
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";


export default {
    setup() { return useValidationRulesSetup(); },

    props: [],
    components: { ItsMenuButton, HeaderAndFooter, Header, RowsAndColumns, Row_1, Row_2 },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();

        const id = this.$route.query.id;
        await this.homepageStore.loadHomepage(id);

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
        ...mapWritableState(useHomepageStore, ["data", "record", "homepage"])
    },

    methods: {

        reload() {
            window.location.reload();


        },

        openPreview(mode) {
            let width = 1200, height = 600;
            if (mode === 'mobile') { width = 390; height = 600; }
            if (mode === 'tablet') { width = 960; height = 500; }

            const features = `width=${width},height=${height},resizable=yes,scrollbars=yes,status=no`;
            window.open(this.preview_src, "_blank", features);
        },

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
