<template>

    <!-- HEADER -->
    <ColBox title="Kopfbereich" icon="mdi-page-layout-header">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">

                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Aktiv" v-model="data.is_active.value" />
                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Fluid (=gesamte Breite)"
                        v-model="data.is_fluid.value" />

                    <v-autocomplete v-model="data.max_width.value"
                        :items="['', '600px', '768px', '960px', '1024px', '1280px', '1920px']" density="comfortable"
                        label="Max. Breite"></v-autocomplete>

                    <v-text-field v-model="data.bg_color.value" label="Hintergrundfarbe (z.B. bg-primary, bg-green)"
                        :rules="[maxLength(255)]" />

                    <v-text-field v-model="data.color.value" label="Textfarbe (z.B. text-primary, text-green)"
                        :rules="[maxLength(255)]" />

                    <v-autocomplete v-model="data.density.value"
                        :items="['default', 'prominent', 'comfortable', 'compact']" density="comfortable"
                        label="Density"></v-autocomplete>

                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Ohne Schatten"
                        v-model="data.is_flat.value" />

                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Ohne Abrundung"
                        v-model="data.is_tile.value" />

                    <v-autocomplete v-model="data.scroll_behavior.value"
                        :items="['hide', 'fully-hide', 'inverted', 'collapse', 'elevate', 'fade-image']"
                        density="comfortable" label="Density"></v-autocomplete>



                </v-col>
            </v-row>
            <v-row no-gutters class="mt-4">
                <v-col cols="6">
                    <v-btn block flat tile text="Abbruch" color="error" prepend-icon="mdi-close"
                        @click="$emit('click-abort')" />
                </v-col>
                <v-col cols="6">
                    <v-btn block flat tile text="Speichern" color="success" prepend-icon="mdi-content-save"
                        @click="onSave" />
                </v-col>

            </v-row>
        </v-form>
    </ColBox>

    <!-- TOPLINE -->
    <PVTopline :data="data.elements.topline" @click-abort="$emit('click-abort')" @click-save="onSave"
        v-if="data.elements?.topline?.component?.value == 'Topline'" />

</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import ColBox from "../components/ColBox.vue";
import PVTopline from "./PVTopline.vue";
export default {
    emits: ['click-save', 'click-abort'],
    setup() { return useValidationRulesSetup(); },

    props: ['data'],
    components: { ColBox, PVTopline },

    data() {
        return {
            is_valid: false,
        };
    },

    methods: {
        async onSave() {
            this.is_valid = false; await this.$refs.form.validate(); if (!this.is_valid) return;
            this.$emit('click-save');
        }

    }



}
</script>
