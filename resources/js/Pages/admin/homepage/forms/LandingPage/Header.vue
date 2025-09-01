<template>
    <v-card>
        <v-card-title class="d-flex flex-column">
            <div class="d-flex flex-row align-center justify-space-between">
                <div>Kopfzeile</div>
                <v-btn flat size="small" color="warning" variant="tonal" @click="$emit('clickAction', '')">
                    <v-icon icon="mdi-close" />
                </v-btn>
            </div>
        </v-card-title>
        <v-card-subtitle>
            <div>{{ header?.name }}</div>
        </v-card-subtitle>
        <v-card-text>
            <!-- Rahmen -->
            <v-checkbox hide-details label="Rahmen" :color="header.structure.props.border ? 'success' : ''"
                v-model="header.structure.props.border" />

            <!-- Farbe -->
            <div class="d-flex flex-row flex-wrap ga-2">
                <div class="color-box first d-flex align-center justify-center">A</div>
                <div class="color-box second d-flex align-center justify-center">B</div>
                <div class="color-box third d-flex align-center justify-center">C</div>
            </div>
            <v-select label="Farbe" v-model="header.structure.props.color" :items="colorItems" item-title="label"
                item-value="value" />

            <!-- Density -->
            <v-select label="Vordefinierte Höhe" v-model="header.structure.props.density" :items="densityItems"
                item-title="label" item-value="value" />
            <div class="text-caption font-weight-medium">oder</div>

            <!-- Height -->
            <v-number-input clearable label="Individuelle Höhe (24-128px)" v-model="header.structure.props.height"
                :min="24" :max="128" />

            <!-- Elevation -->
            <v-number-input label="Schatten (0-24px)" v-model="header.structure.props.elevation" :min="0" :max="24" />

            <!-- Flat -->
            <v-checkbox hide-details label="Flach" :color="header.structure.props.flat ? 'success' : ''"
                v-model="header.structure.props.flat" />

            <!-- Scroll behavior -->
            <v-select label="Rollverhalten" v-model="header.structure.props.scroll_behavior"
                :items="scrollBehaviorItems" item-title="label" item-value="value" />

            <div class="d-flex flex-row justify-end">
                <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                    @click="$emit('confirmAction', header)">
                    Bestätigen
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>
<script>

export default {
    props: ['header', 'colorItems', 'densityItems', 'scrollBehaviorItems'],
    emits: ['clickAction', 'confirmAction']

}
</script>
<style scoped>
.color-box {
    width: 100px;
    height: 24px;
    border: 1px solid #666666;
}
</style>