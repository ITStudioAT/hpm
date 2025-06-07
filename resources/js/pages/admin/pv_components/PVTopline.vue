<template>

    <!-- TOPLINE -->
    <ColBox title="1. Zeile">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Aktiv" v-model="data.is_active.value" />

                    <v-autocomplete v-model="data.height.value"
                        :items="['14px', '18px', '22px', '26px', '30px', '38px', '46px']" density="comfortable"
                        label="Höhe"></v-autocomplete>
                    <v-text-field v-model="data.bg_color.value" label="Hintergrundfarbe (z.B. bg-primary, bg-green)"
                        :rules="[maxLength(255)]" />

                    <v-autocomplete v-model="data.columns.value" :items="[1, 2, 3]" density="comfortable"
                        label="Spalten" />

                    <v-autocomplete v-model="data.elements.col_1.component.value" :items="['ShortText']"
                        density="comfortable" label="1. Spalte: Typ" v-if="data.columns?.value >= 1" />

                    <v-autocomplete v-model="data.elements.col_2.component.value" :items="['ShortText']"
                        density="comfortable" label="2. Spalte: Typ" v-if="data.columns?.value >= 2" />

                    <v-autocomplete v-model="data.elements.col_3.component.value" :items="['ShortText']"
                        density="comfortable" label="3. Spalte: Typ" v-if="data.columns?.value >= 3" />

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

    <!-- ELEMENTE DER TOPLINE -->

    <component :is="'PV' + data.elements['col_' + i].component.value" :data="data.elements['col_' + i]"
        :title="'Spalte ' + i" v-for="i in data.columns.value" @click-abort="$emit('click-abort')"
        @click-save="onSave" />




</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import ColBox from "../components/ColBox.vue";
import PVShortText from "./PVShortText.vue";
export default {
    emits: ['click-save', 'click-abort'],
    setup() { return useValidationRulesSetup(); },

    props: ['data'],
    components: { ColBox, PVShortText },


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
