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

            <v-row class="w-100 mb-2" v-if="index && action === ''">
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Kopf- und Fußzeile</v-card-title>
                        <v-card-text>
                            <div class="d-flex flex-row align-center justify-space-between">
                                <v-checkbox v-model="index.structure.header.is_visible" color="success"
                                    label="Kopfzeile" hide-details @update:model-value="update" />
                                <v-btn flat color="primary" :disabled="!index.structure.header.is_visible"
                                    @click="action = 'header'">
                                    Bearbeiten
                                </v-btn>
                            </div>
                            <div class="d-flex flex-row align-center justify-space-between">
                                <v-checkbox v-model="index.structure.footer.is_visible" color="success" label="Fußzeile"
                                    hide-details @update:model-value="update" />
                                <v-btn flat color="primary" :disabled="!index.structure.footer.is_visible"
                                    @click="action = 'footer'">
                                    Bearbeiten
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <v-expand-transition>
                <v-row class="w-100 mb-2" v-if="index && action === 'header'">
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <v-card>
                            <v-card-title class="d-flex flex-column">
                                <div class="d-flex flex-row align-center justify-space-between">
                                    <div>Kopfzeile</div>
                                    <v-btn flat size="small" color="warning" variant="tonal" @click="action = ''">
                                        <v-icon icon="mdi-close" />
                                    </v-btn>
                                </div>
                            </v-card-title>
                            <v-card-subtitle>
                                <div>{{ header?.name }}</div>
                            </v-card-subtitle>
                            <v-card-text>
                                <!-- Rahmen -->
                                <v-checkbox hide-details label="Rahmen"
                                    :color="header.structure.props.border ? 'success' : ''"
                                    v-model="header.structure.props.border" />

                                <!-- Farbe -->
                                <div class="d-flex flex-row flex-wrap ga-2">
                                    <div class="color-box first d-flex align-center justify-center">A</div>
                                    <div class="color-box second d-flex align-center justify-center">B</div>
                                    <div class="color-box third d-flex align-center justify-center">C</div>
                                </div>
                                <v-select label="Farbe" v-model="header.structure.props.color" :items="colorItems"
                                    item-title="label" item-value="value" />

                                <!-- Density -->
                                <v-select label="Vordefinierte Höhe" v-model="header.structure.props.density"
                                    :items="densityItems" item-title="label" item-value="value" />
                                <div class="text-caption font-weight-medium">oder</div>

                                <!-- Height -->
                                <v-number-input clearable label="Individuelle Höhe (24-128px)"
                                    v-model="header.structure.props.height" :min="24" :max="128" />

                                <!-- Elevation -->
                                <v-number-input label="Schatten (0-24px)" v-model="header.structure.props.elevation"
                                    :min="0" :max="24" />

                                <!-- Flat -->
                                <v-checkbox hide-details label="Flach"
                                    :color="header.structure.props.flat ? 'success' : ''"
                                    v-model="header.structure.props.flat" />

                                <!-- Scroll behavior -->
                                <v-select label="Rollverhalten" v-model="header.structure.props.scroll_behavior"
                                    :items="scrollBehaviorItems" item-title="label" item-value="value" />




                                <div class="d-flex flex-row justify-end">
                                    <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                                        @click="confirm('header')">
                                        Bestätigen
                                    </v-btn>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- Zeilen/Spalten - Aufbau der Kopfzeile -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <v-card>
                            <v-card-title class="d-flex flex-column">
                                <div class="d-flex flex-row align-center justify-space-between">
                                    <div>Zeilen / Spalten</div>
                                    <v-btn flat size="small" color="warning" variant="tonal" @click="action = ''">
                                        <v-icon icon="mdi-close" />
                                    </v-btn>
                                </div>
                            </v-card-title>
                            <v-card-subtitle>
                                <div>Aufbau der Kopfzeile</div>
                            </v-card-subtitle>
                            <v-card-text>
                                <!-- Rows (Hinweis: dein Schema erlaubt aktuell 1..2 statisch; count wirkt rein visuell) -->
                                <v-number-input label="Zeilen (1-2)" v-model="header.structure.rows.count" :min="1"
                                    :max="2" />

                                <!-- ZEILE 1-->
                                <v-card>

                                    <div class="bg-primary pa-1">Zeile 1:</div>



                                    <v-tabs v-model="line_1_options" align-tabs="center">
                                        <v-tab :value="1">Desktop</v-tab>
                                        <v-tab :value="2">Tablet</v-tab>
                                        <v-tab :value="3">Handy</v-tab>
                                    </v-tabs>
                                    <v-tabs-window v-model="line_1_options">
                                        <!-- DESKTOP -->
                                        <v-tabs-window-item :key="1" :value="1">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_1.desktop.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_1.desktop.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_1.desktop.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_1.desktop.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>

                                        <!-- TABLET -->
                                        <v-tabs-window-item :key="2" :value="2">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_1.tablet.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_1.tablet.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_1.tablet.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_1.tablet.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>

                                        <!-- HANDY -->
                                        <v-tabs-window-item :key="3" :value="3">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_1.handy.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_1.handy.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_1.handy.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_1.handy.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>
                                    </v-tabs-window>
                                </v-card>


                                <!-- ZEILE 2-->
                                <v-card v-if="header.structure.rows.count > 1" class="mt-6">

                                    <div class="bg-primary pa-1">Zeile 2:</div>



                                    <v-tabs v-model="line_2_options" align-tabs="center">
                                        <v-tab :value="1">Desktop</v-tab>
                                        <v-tab :value="2">Tablet</v-tab>
                                        <v-tab :value="3">Handy</v-tab>
                                    </v-tabs>
                                    <v-tabs-window v-model="line_2_options">
                                        <!-- DESKTOP -->
                                        <v-tabs-window-item :key="1" :value="1">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_2.desktop.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_2.desktop.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_2.desktop.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_2.desktop.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>

                                        <!-- TABLET -->
                                        <v-tabs-window-item :key="2" :value="2">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_2.tablet.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_2.tablet.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_2.tablet.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_2.tablet.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>

                                        <!-- HANDY -->
                                        <v-tabs-window-item :key="3" :value="3">
                                            <v-container fluid>
                                                <v-row>
                                                    <v-col>
                                                        <!-- Fluid -->
                                                        <v-checkbox hide-details label="Volle Breite"
                                                            :color="header.structure.rows.row_2.handy.fluid ? 'success' : ''"
                                                            v-model="header.structure.rows.row_2.handy.fluid" />
                                                        <div class="text-caption font-weight-medium">oder</div>

                                                        <!-- max-width -->
                                                        <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                                            v-model="header.structure.rows.row_2.handy.max_width"
                                                            :min="600" :max="1920" />

                                                        <!-- columns.count -->
                                                        <v-number-input label="Anzahl Spalten  (1-3)"
                                                            v-model="header.structure.rows.row_2.handy.columns.count"
                                                            :min="1" :max="3" />


                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-tabs-window-item>
                                    </v-tabs-window>
                                </v-card>


                                <div class="d-flex flex-row justify-end mt-6">
                                    <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                                        @click="confirm('header')">
                                        Bestätigen
                                    </v-btn>
                                </div>

                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- SPALTEN -->
                    <!-- Spalten Zeile 1 -->
                    <v-col cols="12" md="6" lg="4" xl="3">
                        <v-card>
                            <v-card-title class="d-flex flex-column">
                                <div class="d-flex flex-row align-center justify-space-between">
                                    <div>Zeile 1</div>
                                    <v-btn flat size="small" color="warning" variant="tonal" @click="action = ''">
                                        <v-icon icon="mdi-close" />
                                    </v-btn>
                                </div>
                            </v-card-title>
                            <v-card-subtitle>
                                <div>Festlegen der Spalten</div>
                            </v-card-subtitle>
                            <v-card-text>
                                <v-tabs v-model="line_1_col_options" align-tabs="center">
                                    <v-tab :value="1">Desktop</v-tab>
                                    <v-tab :value="2">Tablet</v-tab>
                                    <v-tab :value="3">Handy</v-tab>
                                </v-tabs>
                                <v-tabs-window v-model="line_1_col_options">
                                    <!-- DESKTOP -->
                                    <v-tabs-window-item :key="1" :value="1">
                                        <v-container fluid>
                                            <v-row>
                                                <v-col>
                                                    <v-expansion-panels>
                                                        <v-expansion-panel :key="1" title="Spalte 1">
                                                            <v-expansion-panel-text>
                                                                <!-- Ausrichtung / Justify -->
                                                                <v-select label="Ausrichtung"
                                                                    v-model="header.structure.rows.row_1.desktop.columns.col_1.justify"
                                                                    :items="justifyItems" item-title="label"
                                                                    item-value="value" />

                                                                <!-- has_menu -->
                                                                <v-checkbox hide-details label="Menü"
                                                                    :color="header.structure.rows.row_1.desktop.columns.col_1.has_menu ? 'success' : ''"
                                                                    v-model="header.structure.rows.row_1.desktop.columns.col_1.has_menu" />
                                                                <v-expand-transition>
                                                                    <div
                                                                        v-if="header.structure.rows.row_1.desktop.columns.col_1.has_menu">
                                                                        <div class="bg-secondary pa-1">Menü:</div>
                                                                        <div
                                                                            v-if="header.structure.rows.row_1.desktop.columns.col_1.menu_name">
                                                                            {{
                                                                                header.structure.rows.row_1.desktop.columns.col_1.menu_name
                                                                            }}</div>
                                                                        <div class="text-warning" v-else>Kein Menü
                                                                            ausgewählt</div>

                                                                        <div class="d-flex flex-row justify-end">
                                                                            <v-btn flat color="primary" variant="tonal"
                                                                                prepend-icon="mdi-form-select"
                                                                                @click="">
                                                                                Menü auswählen
                                                                            </v-btn>
                                                                        </div>
                                                                    </div>
                                                                </v-expand-transition>



                                                                <!-- has_image -->
                                                                <v-divider color="primary" opacity=0.5 class="mt-4" />
                                                                <v-checkbox hide-details label="Bild/Logo"
                                                                    :color="header.structure.rows.row_1.desktop.columns.col_1.has_image ? 'success' : ''"
                                                                    v-model="header.structure.rows.row_1.desktop.columns.col_1.has_image" />
                                                                <v-expand-transition>
                                                                    <div
                                                                        v-if="header.structure.rows.row_1.desktop.columns.col_1.has_image">
                                                                        <div class="bg-secondary pa-1">Bild:</div>
                                                                        <div
                                                                            v-if="header.structure.rows.row_1.desktop.columns.col_1.image">
                                                                            BILD</div>
                                                                        <div class="text-warning" v-else>Kein Bild
                                                                            ausgewählt</div>
                                                                        <div class="d-flex flex-row justify-end">
                                                                            <v-btn flat color="primary" variant="tonal"
                                                                                prepend-icon="mdi-form-select"
                                                                                @click="is_select = true">
                                                                                Bild auswählen
                                                                            </v-btn>
                                                                        </div>
                                                                    </div>
                                                                </v-expand-transition>

                                                                <!-- has_text -->
                                                                <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                                                <v-checkbox hide-details label="Text"
                                                                    :color="header.structure.rows.row_1.desktop.columns.col_1.has_text ? 'success' : ''"
                                                                    v-model="header.structure.rows.row_1.desktop.columns.col_1.has_text" />
                                                                <v-expand-transition>
                                                                    <div
                                                                        v-if="header.structure.rows.row_1.desktop.columns.col_1.has_text">
                                                                        <div class="bg-secondary pa-1">Text:
                                                                        </div>
                                                                        <v-text-field flat :rules="[maxLength(128)]"
                                                                            :counter="128"
                                                                            v-model="header.structure.rows.row_1.desktop.columns.col_1.text" />
                                                                        <!-- Textvariante -->
                                                                        <v-select label="Textvariante"
                                                                            v-model="header.structure.rows.row_1.desktop.columns.col_1.text_variant"
                                                                            :items="textVariantItems" item-title="label"
                                                                            item-value="value" />
                                                                    </div>
                                                                </v-expand-transition>
                                                            </v-expansion-panel-text>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>

                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-tabs-window-item>
                                </v-tabs-window>
                                <div class="d-flex flex-row justify-end">
                                    <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                                        @click="confirm('header')">
                                        Bestätigen
                                    </v-btn>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>

                </v-row>

            </v-expand-transition>

            <Select v-if="is_select" @abort="is_select = false" @takeIt="selectTakeIt" />

            <v-row>
                {{ header.structure.rows.row_1.desktop }}
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
import Select from "@mediamanager/js/pages/admin/select/Select.vue";

// <-- NEU: generierte Defaults/Schemas verwenden
import { cloneStructure, HPM_SCHEMAS } from "@/constants/structures.generated";

export default {
    setup() { return useValidationRulesSetup(); },

    props: ["homepage"],
    components: { ItsMenuButton, Select },

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
            this.index.homepage_id +
            "&record_id=" +
            this.index.id;

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
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
            is_valid: false,
            is_select: false,
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
        selectTakeIt(data) {
            console.log(data); // shows path and filename
            this.is_select = false;
        },
    }
};
</script>

<style scoped>
.color-box {
    width: 100px;
    height: 24px;
    border: 1px solid #666666;
}
</style>
