<template>

    <ColBox :title="item.title" :icon="item.icon" v-if="isDependencyOk(item)">
        <v-form ref="form" v-model="is_valid">
            <v-row no-gutters>
                <v-col cols="12">
                    <template v-for="(prop, i) in item.properties" class="mb-2">

                        <v-switch base-color="error" color="success" density="compact" flat hide-details
                            false-icon="mdi-close" true-icon="mdi-check" :label="prop.props.label"
                            v-model="prop.props.value"
                            v-if="prop.props.selection.type === 'switch_yes_no' && isPropDependencyOk(prop)"
                            @update:modelValue="updateSwitch(prop)" />

                        <v-text-field density="compact" flat :label="prop.props.label" v-model="prop.props.value"
                            :rules="[integer(),
                            prop.props.selection.min != null ? min(prop.props.selection.min) : () => true,
                            prop.props.selection.max != null ? max(prop.props.selection.max) : () => true]"
                            v-if="prop.props.selection.type === 'integer' && isPropDependencyOk(prop)" />


                        <v-text-field density="compact" flat :label="prop.props.label" v-model="prop.props.value"
                            :rules="[prop.props.selection.required === true ? required() : () => true,
                            prop.props.selection.min != null ? minLength(prop.props.selection.min) : () => true,
                            prop.props.selection.max != null ? maxLength(prop.props.selection.max) : () => true]"
                            v-if="prop.props.selection.type === 'text' && isPropDependencyOk(prop)" />

                        <v-autocomplete density="compact" flat :label="prop.props.label" v-model="prop.props.value"
                            :items="prop.props.selection.items" item-title="name" item-value="value" @update:modelValue="val => {
                                const selected = prop.props.selection.items.find(item => item.value === val);
                                if (selected) prop.props.name = selected.name;
                            }" v-if="prop.props.selection.type === 'list_with_names' && isPropDependencyOk(prop)" />

                        <v-autocomplete density="compact" flat :label="prop.props.label" v-model="prop.props.value"
                            :items="prop.props.selection.items" item-title="name" item-value="value"
                            v-if="prop.props.selection.type === 'list' && isPropDependencyOk(prop)" />

                        <div v-if="prop.props.selection.type === 'image' && isPropDependencyOk(prop)">
                            <div v-if="!prop.props.value">Kein Bild ausgewählt</div>
                            <div v-else>BILD</div>
                            <v-btn flat tile variant="tonal" text="Bild auswählen" />
                        </div>

                    </template>

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

    props: ['item', 'items'],
    components: { ColBox },

    data() {
        return {
            is_valid: false,
        };
    },

    methods: {
        isDependencyOk(item) {
            if (!item.hasOwnProperty('if')) return true;
            return this.evaluateCondition(this.items, item.if);
        },

        isPropDependencyOk(prop) {
            if (!prop.hasOwnProperty('if')) return true;
            var answer = this.evaluateCondition(this.items, prop.if);
            return answer;
        },

        evaluateCondition(items, condition) {
            // Unterstützt mehrere Bedingungen mit &&
            const conditions = condition.split('&&').map(c => c.trim());

            for (const cond of conditions) {
                const operators = ['>=', '<=', '==', '!=', '>', '<'];
                const operator = operators.find(op => cond.includes(op));
                if (!operator) return false;

                const [path, expectedRaw] = cond.split(operator).map(s => s.trim());

                let expectedValue;
                try {
                    expectedValue = JSON.parse(expectedRaw);
                } catch {
                    expectedValue = expectedRaw.replace(/^['"]|['"]$/g, ''); // Entfernt Anführungszeichen
                }

                const [type, , propertyName] = path.split('.');

                const targetItem = items.find(item =>
                    item.type === type && item.properties?.some(p => p.name === propertyName)
                );
                if (!targetItem) return false;

                const property = targetItem.properties.find(p => p.name === propertyName);
                if (!property) return false;

                let actualValue = property.props.value;

                // Integer-Konvertierung falls möglich
                if (!isNaN(expectedValue) && !isNaN(actualValue)) {
                    expectedValue = Number(expectedValue);
                    actualValue = Number(actualValue);
                }

                switch (operator) {
                    case '==': if (actualValue !== expectedValue) return false; break;
                    case '!=': if (actualValue === expectedValue) return false; break;
                    case '>=': if (actualValue < expectedValue) return false; break;
                    case '<=': if (actualValue > expectedValue) return false; break;
                    case '>': if (actualValue <= expectedValue) return false; break;
                    case '<': if (actualValue >= expectedValue) return false; break;
                    default: return false;
                }
            }

            // Alle Bedingungen erfüllt
            return true;
        }

        ,


        updateSwitch(prop) {
            if (prop.props.value == true) prop.props.name = "Ja"; else prop.props.name = "Nein";

            const index = this.item.properties.findIndex(i => i.name === prop.name);
            if (index !== -1) {
                // Update existing item
                this.item.properties.splice(index, 1, prop);
            }

        },

        async onSave() {
            this.is_valid = false; await this.$refs.form.validate(); if (!this.is_valid) return;
            this.$emit('click-save');
        }
    }



}
</script>
