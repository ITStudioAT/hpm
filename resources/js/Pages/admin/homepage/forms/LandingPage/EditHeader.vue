<template>



    <!-- HEADER-->
    <v-expand-transition>

        <v-row class="w-100 mb-2">
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

    <!-- VORSCHAU -->

    <v-row>
        <v-col>
            <its-menu-button title="Vorschau" subtitle="Neues Fenster" icon="mdi-eye" color="primary"
                @click="openPreview" v-if="is_preview_intern" />

            <its-menu-button title="Vorschau" subtitle="schließen" icon="mdi-close" color="warning"
                @click="closePreview" v-if="!is_preview_intern" />

        </v-col>

    </v-row>



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
</template>
<script>
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import Header from "./Header.vue";
import RowsAndColumns from "./RowsAndColumns.vue";
import Row_1 from "./Row_1.vue";
import Row_2 from "./Row_2.vue";
import { COLOR_ITEMS, DENSITY_ITEMS, SCROLL_BEHAVIOR_ITEMS, JUSTIFY_ITEMS, TEXT_VARIANT_ITEMS } from "@/constants/uiOptions";
export default {
    emits: ['confirmHeader'],
    props: ['index', 'header', 'reloadKey'],
    components: { ItsMenuButton, Header, RowsAndColumns, Row_1, Row_2 },

    async beforeMount() {
        // ---------- Preview ----------
        this.preview_src =
            `/homepage/example/header_and_footer?homepage_id=${this.index.homepage_id}&record_id=${this.index.id}`
    },

    data() {
        return {

            colorItems: COLOR_ITEMS,
            densityItems: DENSITY_ITEMS,
            scrollBehaviorItems: SCROLL_BEHAVIOR_ITEMS,
            justifyItems: JUSTIFY_ITEMS,
            textVariantItems: TEXT_VARIANT_ITEMS,

            is_preview_intern: true,
            preview_src: null,
            blank_window: null,
        }
    },

    methods: {
        confirmHeader(header) {
            this.$emit('confirmHeader', header)
        },

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
    }
}
</script>