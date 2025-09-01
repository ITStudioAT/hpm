<template>
    <v-row class="w-100 mb-2">
        <v-col cols="12" md="6" lg="4" xl="3">
            <v-card>
                <v-card-title>Kopf- und Fußzeile</v-card-title>
                <v-card-text>
                    <div class="d-flex flex-row align-center justify-space-between">
                        <v-checkbox v-model="index.structure.header.is_visible" color="success"
                            label="Kopfzeile anzeigen" hide-details @update:model-value="$emit('update')" />
                        <v-btn flat color="primary" :disabled="!index.structure.header.is_visible"
                            @click="$emit('clickAction', 'header')">
                            Bearbeiten
                        </v-btn>
                    </div>
                    <div class="d-flex flex-row align-center justify-space-between">
                        <v-checkbox v-model="index.structure.footer.is_visible" color="success"
                            label="Fußzeile anzeigen" hide-details @update:model-value="$emit('update')" />
                        <v-btn flat color="primary" :disabled="!index.structure.footer.is_visible"
                            @click="$emit('clickAction', 'footer')">
                            Bearbeiten
                        </v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: ['index'],
    emits: ['clickAction', 'update']
}

</script>