<template>



    <!-- HEADER-->


    <v-row class="w-100 mb-2">
        <!-- Spalte KOPFZEILE -->
        <v-col cols="12" md="6" lg="4" xl="3">
            <Header :header="header" :colorItems="colorItems" :densityItems="densityItems"
                :scrollBehaviorItems="scrollBehaviorItems" @abort="$emit('abort')"
                @confirmAction="(header) => confirmHeader(header)" />
        </v-col>

        <!-- Zeilen/Spalten - Aufbau der Kopfzeile -->
        <v-col cols="12" md="6" lg="4" xl="3">
            <RowsAndColumns :header="header" :colorItems="colorItems" @abort="$emit('abort')"
                @confirmAction="(header) => confirmHeader(header)" />

        </v-col>

        <!-- SPALTEN -->
        <!-- Spalten Zeile 1 -->
        <v-col cols="12" md="6" lg="4" xl="3">
            <Row_1 :header="header" :justifyItems="justifyItems" :textVariantItems="textVariantItems"
                @abort="$emit('abort')" @confirmAction="(header) => confirmHeader(header)" />

            <Row_2 :header="header" :justifyItems="justifyItems" :textVariantItems="textVariantItems"
                @abort="$emit('abort')" @confirmAction="(header) => confirmHeader(header)"
                v-if="header.structure.rows.count > 1" />
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
    emits: ['confirmHeader', 'abort'],
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

    }
}
</script>