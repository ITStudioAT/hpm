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
                            <v-text-field autofocus v-model="data.name" label="Name der Homepage"
                                required></v-text-field>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Farben</v-card-title>
                        <v-card-text>
                            <v-text-field v-model="data.structure.colors.bgColor" label="Hintergrundfarbe"
                                required></v-text-field>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title class="d-flex flex-row align-center justify-space-between">
                            <div>Schriften</div>
                            <v-btn flat @click="clickInfo('fonts')" :color="info == 'fonts' ? 'success' : ''"><v-icon
                                    icon="mdi-information-box" /></v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-autocomplete v-model="data.structure.fonts.fontType" :items="fontTypes"
                                item-title="label" item-value="value" label="Schriftentypus auswählen" />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-form>

        <!-- Schriften -->
        <v-expand-transition>
            <FontsetShowCase v-if="info == 'fonts'" />
        </v-expand-transition>
    </v-container>
</template>

<script>
import { deepMergeDefaults } from "@/helpers/merge";
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNavigationStore } from "@/stores/admin/NavigationStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";

import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";
import Overview from "@/pages/admin/examples/fonts/Overview.vue";
import FontsetShowCase from "@/pages/admin/examples/FontsetShowCase.vue";

export default {
    props: ["homepage"],

    components: { ItsMenuButton, ItsGridBox, Overview, FontsetShowCase },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.navigationStore = useNavigationStore();
        this.homepageStore = useHomepageStore();

        const defaultStructure = {
            colors: {
                frontColor: "#000000",
                bgColor: "#ffffff",
            },
            fonts: {
                fontType: "",
            },
        };

        this.data = {
            ...this.homepage,
            structure: deepMergeDefaults(
                this.homepage.structure ?? {},
                defaultStructure
            ),
        };


        if (!this.data.structure.fonts.fontType) {
            this.data.structure.fonts.fontType = 'default';
        }
    },
    unmounted() { },

    data() {
        return {
            adminStore: null,
            navigationStore: null,
            homepageStore: null,
            info: null,

            // the selected (value) will be one of: 'nobel', 'default', 'education', 'manual'
            selectedFontType: "default",

            // items array: { value, label }
            fontTypes: [
                { value: "default", label: "Standard" },
                { value: "manual", label: "Dokus" },
                { value: "education", label: "Education" },
                { value: "nobel", label: "Nobel" },
            ],
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data"]),
    },

    methods: {
        clickInfo(action) {
            if (this.info == action) {
                this.info = null;
            } else {
                this.info = action;
            }
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
            if (typeof this[methodName] === "function") {
                this[methodName]();
            }
        },
    },
};
</script>
