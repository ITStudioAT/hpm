<template>

    <ColBox :title="title">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-text-field v-model="my_data.bg_color.value" label="Hintergrundfarbe (z.B. bg-primary, bg-green)"
                        :rules="[maxLength(255)]" />

                    <v-text-field v-model="my_data.color.value" label="Textfarbe (z.B. text-primary, text-green)"
                        :rules="[maxLength(255)]" />

                    <v-autocomplete v-model="my_data.size.value"
                        :items="['text-body-1', 'text-body-2', 'text-subtitle-1', 'text-subtitle-2', 'text-button', 'text-caption', 'text-overline']"
                        density="comfortable" label="Textgröße"></v-autocomplete>

                    <v-autocomplete v-model="my_data.font.value"
                        :items="['font-weight-black', 'font-weight-bold', 'font-weight-medium', 'font-weight-regular', 'font-weight-light', 'font-weight-thin']"
                        density="comfortable" label="Schriftdarstellung"></v-autocomplete>

                    <v-autocomplete v-model="my_data.justify.value"
                        :items="['justify-start', 'justify-center', 'justify-end']" density="comfortable"
                        label="Ausrichtung"></v-autocomplete>

                    <v-text-field v-model="my_data.text.value" label="Text" :rules="[maxLength(255)]" />

                    <v-text-field v-model="my_data.phone.value" label="telefon" prepend-icon="mdi-phone"
                        :rules="[maxLength(255)]" />

                    <v-text-field v-model="my_data.mail.value" label="telefon" prepend-icon="mdi-mail"
                        :rules="[maxLength(255)]" />

                    <v-text-field v-model="my_data.padding.value" label="Innenabstand (z.B. px-2, px-4)"
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
        <div>
            DATA:
            {{ data }}
        </div>
        <div>
            MY_DATA:
            {{ my_data }}
        </div>

    </ColBox>


</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import ColBox from "../components/ColBox.vue";
export default {
    emits: ['click-save', 'click-abort'],
    setup() { return useValidationRulesSetup(); },

    beforeMount() {
        this.my_data = this.data;
        this.sync();
    },

    props: ['data', 'title'],
    components: { ColBox },


    data() {
        return {
            my_data: {},
            is_valid: false,
            structure: {
                component: { value: 'ShortText' },
                color: { value: 'text-black' },
                bg_color: { value: '' },
                size: { value: 'text-body-2' },
                font: { value: 'font-weight-medium ' },
                justify: { value: 'justify-start' },
                text: { value: 'Herzlich Wollkommen!' },
                padding: { value: 'px-2' },
                mail: { value: '06272' },
                phone: { value: '' },
            },
        };
    },

    methods: {
        async onSave() {
            this.is_valid = false; await this.$refs.form.validate(); if (!this.is_valid) return;
            this.$emit('click-save');
        },

        sync() {
            Object.keys(this.structure).forEach(key => {
                if (!(key in this.my_data)) this.my_data[key] = this.structure[key];
            })

        },


    }

}
</script>
