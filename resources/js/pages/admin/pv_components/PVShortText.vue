<template>

    <ColBox :title="title">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-text-field v-model="data.bg_color.value" label="Hintergrundfarbe (z.B. bg-primary, bg-green)"
                        :rules="[maxLength(255)]" />

                    <v-text-field v-model="data.color.value" label="Textfarbe (z.B. text-primary, text-green)"
                        :rules="[maxLength(255)]" />

                    <v-autocomplete v-model="data.size.value"
                        :items="['text-body-1', 'text-body-2', 'text-subtitle-1', 'text-subtitle-2', 'text-button', 'text-caption', 'text-overline']"
                        density="comfortable" label="Textgröße"></v-autocomplete>

                    <v-autocomplete v-model="data.font.value"
                        :items="['font-weight-black', 'font-weight-bold', 'font-weight-medium', 'font-weight-regular', 'font-weight-light', 'font-weight-thin']"
                        density="comfortable" label="Schriftdarstellung"></v-autocomplete>

                    <v-autocomplete v-model="data.justify.value"
                        :items="['justify-start', 'justify-center', 'justify-end']" density="comfortable"
                        label="Ausrichtung"></v-autocomplete>

                    <v-text-field v-model="data.text.value" label="Text" :rules="[maxLength(255)]" />

                    <v-text-field v-model="data.padding.value" label="Innenabstand (z.B. px-2, px-4)"
                        :rules="[maxLength(255)]" />

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

    props: ['data', 'title'],
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
