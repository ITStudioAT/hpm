<template>

    <ColBox title="Homepage" icon="mdi-home">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Aktiv" v-model="data.is_active.value" />


                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Kopfbereich"
                        v-model="data.elements.header.is_active.value" />

                    <v-autocomplete v-model="data.elements.header.component.value" :items="['Header']"
                        density="comfortable" label="Typ" v-if="data.elements.header.is_active.value"></v-autocomplete>

                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Hauptbereich"
                        v-model="data.elements.main.is_active.value" />
                    <v-autocomplete v-model="data.elements.main.component.value" :items="['Main']" density="comfortable"
                        label="Typ" v-if="data.elements.main.is_active.value"></v-autocomplete>


                    <v-switch base-color="error" color="success" density="compact" flat hide-details
                        false-icon="mdi-close" true-icon="mdi-check" label="Fußbereich"
                        v-model="data.elements.footer.is_active.value" />
                    <v-autocomplete v-model="data.elements.footer.component.value" :items="['Footer']"
                        density="comfortable" label="Typ" v-if="data.elements.footer.is_active.value"></v-autocomplete>

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

</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import ColBox from "../components/ColBox.vue";
export default {
    emits: ['click-save', 'click-abort'],
    setup() { return useValidationRulesSetup(); },

    props: ['data'],
    components: { ColBox },

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
