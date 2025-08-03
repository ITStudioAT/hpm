<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <div class="text-h4 mb-4">Beispiele zu Schriften</div>
        <!-- Menüleiste oben -->
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>

            <!--
            <its-menu-button title="Überblick" :color="font == null ? 'primary' : 'secondary'" @click="font = null" />
-->

            <its-menu-button title="Standard" :color="font == 'roboto' ? 'primary' : 'secondary'"
                @click="font = 'roboto'" />

            <its-menu-button title="Nobel" :color="font == 'nobel' ? 'primary' : 'secondary'" @click="font = 'nobel'" />

            <its-menu-button title="Education" :color="font == 'education' ? 'primary' : 'secondary'"
                @click="font = 'education'" />

            <its-menu-button title="Manual" :color="font == 'manual' ? 'primary' : 'secondary'"
                @click="font = 'manual'" />
        </v-row>

        <Nobel v-if="font === 'nobel'" />
        <Education v-if="font === 'education'" />
        <Manual v-if="font === 'manual'" />
        <Roboto v-if="font === 'roboto'" />

        <div v-for="weight in [300, 400, 500, 600, 700, 800, 900]" :key="weight" class="mb-6" v-if="!font">
            <h4>Gewicht {{ weight }}</h4>

            <!-- Für jede Typography-Variante -->
            <div v-for="variant in ['text-body-1', 'text-h6', 'text-h4']" :key="variant" class="mb-4">
                <div class="mb-2 font-medium">
                    {{ variant }}
                </div>

                <!-- Für jede Schrift -->
                <div v-for="font in [
                    'Roboto',
                    'SourceSans',
                    'NotoSans',
                    'IbmPlex',
                    'Cinzel',
                ]" :key="font + '-' + variant" :class="variant" :style="{ fontFamily: font, fontWeight: weight }"
                    class="mb-1">
                    {{ font }} – Variante {{ variant }} – Gewicht {{ weight }} –
                    äöüß – The quick brown fox jumps over the lazy dog.
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import Nobel from "@/Pages/admin/examples/fonts/Nobel.vue";
import Education from "@/Pages/admin/examples/fonts/Education.vue";
import Manual from "@/Pages/admin/examples/fonts/Manual.vue";
import Roboto from "@/Pages/admin/examples/fonts/Roboto.vue";

export default {
    components: { ItsMenuButton, Nobel, Education, Manual, Roboto },

    async beforeMount() { },

    unmounted() { },

    data() {
        return {
            font: "roboto",
        };
    },

    computed: {},

    methods: {},
};
</script>
