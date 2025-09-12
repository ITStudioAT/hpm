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
            <HeaderAndFooter :index="index" :header="header" @update="update" @clickAction="action = $event"
                v-if="index && action === ''" />

            <!-- Edit Header -->
            <v-expand-transition>
                <EditHeader :index="index" :header="header" :reloadKey="reloadKey" @confirmHeader="confirmHeader"
                    @abort="action = ''" v-if="index && action === 'header'" />
            </v-expand-transition>

            <!-- Preview -->
            <Preview :index="index" :reloadKey="reloadKey" />

            HEADER:
            {{ header }}


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

import EditHeader from "./LandingPage/EditHeader.vue";
import Preview from "./LandingPage/Preview.vue";

// <-- NEU: generierte Defaults/Schemas verwenden
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";



export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, HeaderAndFooter, EditHeader, Preview },

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



        this.is_ready = true
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            is_ready: false,

            action: "",
            index: null,
            index_90: null,
            header: null,
            header_90: null,
            footer: null,
            footer_90: null,


            is_valid: false,
            is_select: '',
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
            blank_window: null,
            reloadKey: 0,


        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data", "record"])
    },

    methods: {

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

    }
};
</script>
