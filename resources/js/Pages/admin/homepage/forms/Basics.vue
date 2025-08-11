<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100 mb-2" no-gutters>
            <v-col>
                <div class="bg-primary pa-2 text-h5">Basics</div>
            </v-col>
        </v-row>

        <v-form ref="form" @submit.prevent="$emit('save')">
            <!-- Menüleiste oben -->
            <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                <its-menu-button title="Abbruch" icon="mdi-close" color="warning" @click="abort" />
                <its-menu-button title="Speichern" type="submit" icon="mdi-content-save" color="success"
                    @click="save" />
            </v-row>

            <v-row class="w-100 mb-2" v-if="data">
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Einstellungen</v-card-title>
                        <v-card-text>
                            <v-text-field autofocus v-model="data.name" label="Name der Homepage" required />
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Farbenprofil (read-only + mini preview) -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            <span>Farbenprofil</span>
                            <v-btn size="small" variant="tonal" :color="info == 'fonts' ? 'success' : 'primary'"
                                :prepend-icon="info == 'fonts' ? 'mdi-close' : 'mdi-eye'" @click="clickInfo('fonts')">
                                Auswahl
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-caption text-medium-emphasis mb-1">Ausgewähltes Colorset</div>
                            <div class="mini-row">
                                <div class="text-body-1 font-weight-medium mr-4">{{ colorsetLabel }}</div>
                                <ColorsetMini :value="data.structure.colors.colorset || 'default'" />
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Schriftenprofil (read-only + mini preview) -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            <span>Schriftenprofil</span>
                            <v-btn size="small" variant="tonal" :color="info == 'fonts' ? 'success' : 'primary'"
                                :prepend-icon="info == 'fonts' ? 'mdi-close' : 'mdi-eye'" @click="clickInfo('fonts')">
                                Auswahl
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-caption text-medium-emphasis mb-1">Ausgewähltes Schriftbild</div>
                            <div class="mini-row">
                                <div class="text-body-1 font-weight-medium mr-4">{{ fontsetLabel }}</div>
                                <FontsetMini :value="data.structure.fonts.fontset || 'default'" />
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-form>

        <!-- Schriften & Farben (Blade-Picker unten ein-/ausblenden) -->
        <v-expand-transition>
            <FontsetShowCase v-if="info === 'fonts' && data" :colorset="data.structure.colors.colorset || 'default'"
                :fontset="data.structure.fonts.fontset || 'default'"
                @update:colorset="val => (data.structure.colors.colorset = val)"
                @update:fontset="val => (data.structure.fonts.fontset = val)" />
        </v-expand-transition>
    </v-container>
</template>

<script>
import { deepMergeDefaults } from "@/helpers/merge";
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNavigationStore } from "@/stores/admin/NavigationStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import { useFontsetStore } from "@/stores/admin/FontsetStore";
import { useColorsetStore } from "@/stores/admin/ColorsetStore";

import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";
import Overview from "@/pages/admin/examples/fonts/Overview.vue";
import FontsetShowCase from "@/pages/admin/examples/FontsetShowCase.vue";
import ColorsetMini from "@/pages/admin/examples/previews/ColorsetMini.vue";
import FontsetMini from "@/pages/admin/examples/previews/FontsetMini.vue";

export default {
    props: ["homepage"],
    components: { ItsMenuButton, ItsGridBox, Overview, FontsetShowCase, ColorsetMini, FontsetMini },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.navigationStore = useNavigationStore();
        this.homepageStore = useHomepageStore();
        this.fontsetStore = useFontsetStore();
        this.colorsetStore = useColorsetStore();

        await Promise.all([
            this.fontsetStore.loadFontsets(),
            this.colorsetStore.loadColorsets(),
        ]);

        const defaultStructure = {
            colors: { colorset: "" },
            fonts: { fontset: "" },
        };

        this.data = {
            ...this.homepage,
            structure: deepMergeDefaults(this.homepage.structure ?? {}, defaultStructure),
        };

        if (!this.data.structure.fonts.fontset) this.data.structure.fonts.fontset = "default";
        if (!this.data.structure.colors.colorset) this.data.structure.colors.colorset = "default";
    },

    data() {
        return {
            adminStore: null,
            navigationStore: null,
            homepageStore: null,
            fontsetStore: null,
            colorsetStore: null,
            info: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data"]),
        ...mapWritableState(useFontsetStore, ["fontsets"]),
        ...mapWritableState(useColorsetStore, ["colorsets"]),

        colorsetLabel() {
            const val = this.data?.structure?.colors?.colorset || "default";
            const opt = this.colorsetStore?.options?.find(o => o.value === val);
            return opt?.label || val;
        },
        fontsetLabel() {
            const val = this.data?.structure?.fonts?.fontset || "default";
            const opt = this.fontsetStore?.options?.find(o => o.value === val);
            return opt?.label || val;
        },
    },

    methods: {
        clickInfo(action) {
            this.info = this.info === action ? null : action;
        },
        abort() {
            this.data = null;
            this.$emit("abort");
        },
        async save() {
            await this.homepageStore.saveHomepage(this.data);
            this.$emit("save");
        },
        runAction(methodName) {
            if (typeof this[methodName] === "function") this[methodName]();
        },
    },
};
</script>

<style scoped>
.mini-row {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}
</style>
