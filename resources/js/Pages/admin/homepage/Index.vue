<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- Menüleiste oben -->
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button subtitle="Überblick" icon="mdi-home"
                :color="action == 'overview' ? 'primary' : 'secondary'" @click="menuClick('overview')" />

            <its-menu-button subtitle="Leere Homepage" icon="mdi-plus"
                :color="action == 'new' ? 'primary' : 'secondary'" @click="menuClick('new')" />

            <its-menu-button subtitle="Kopie von Online" icon="mdi-content-copy"
                :color="action == 'copy' ? 'primary' : 'secondary'" @click="menuClick('copy')" />


        </v-row>
        <v-row class="w-100" no-gutters>



            <v-col cols="12" sm="4" md="3" xl="2">

                <!-- OVERVIEW -->
                <v-card variant="tonal" v-if="action === 'overview'">
                    <!-- Auswahl, wenn keine Homepage gewählt ist -->
                    <v-card-text v-if="!homepage">
                        <div>Keine Homepage ausgewählt.</div>
                        <div>Bitte wählen Sie eine Homepage zur Bearbeitung aus.</div>

                        <v-list>
                            <v-list-item v-for="item in homepages" :key="item.id"
                                :active="homepage && homepage.id === item.id" @click="selectHomepage(item)" rounded>
                                <v-list-item-title>{{ item.name }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card-text>

                    <!-- Anzeige Homepage, falls eine gewählt ist -->
                    <v-card-text v-if="homepage" class=" h-100 d-flex flex-column align-center justify-center">
                        <div class="text-caption ">Gewählte Homepage:</div>
                        <div class="text-body-1 font-weight-medium bg-primary pa-2">{{ homepage.name }}</div>


                    </v-card-text>
                    <v-card-actions v-if="homepage">
                        <v-row v-if="action_2 === ''">
                            <!-- overview: action_2 == '': Standardauswahl -->
                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-select-remove" @click="homepage = null"
                                    variant="tonal">Alle zeigen</v-btn>
                            </v-col>
                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-web" @click="" variant="tonal" color="success">Go
                                    Online</v-btn>
                            </v-col>
                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-rename" @click="action_2 = 'rename'"
                                    variant="tonal">Umbenennen</v-btn>
                            </v-col>
                            <v-col cols="6">

                            </v-col>

                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-file-edit" :to="'/admin/homepage/edit?id=' + homepage.id"
                                    variant="tonal" color="primary">Anpassen</v-btn>
                            </v-col>

                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-delete" @click="action_2 = 'delete'" variant="tonal"
                                    color="warning">Löschen</v-btn>
                            </v-col>


                        </v-row>

                        <!-- overview: action_2 == 'delete': Löschen -->
                        <v-row v-if="action_2 === 'delete'">
                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-delete-off" @click="action_2 = ''" variant="tonal"
                                    color="primary">Abbruch</v-btn>
                            </v-col>
                            <v-col cols="6">
                                <v-btn block prepend-icon="mdi-delete" @click="handleDelete(homepage.id)"
                                    variant="tonal" color="error">Löschen</v-btn>
                            </v-col>
                        </v-row>

                        <!-- overview: action_2 == 'rename': Umbenennen -->
                        <v-form ref="form" v-model="is_valid" @submit.prevent="handleSave"
                            @keydown.esc.prevent="abortRename">
                            <v-row v-if="action_2 === 'rename'">
                                <v-col cols="12">
                                    <v-text-field autofocus flat rounded="0" v-model="homepage_copy.name"
                                        label="Name der Homepage" :rules="[required(), maxLength(255)]" />
                                </v-col>
                                <v-col cols="6">
                                    <v-btn block prepend-icon="mdi-close" @click="abortRename" variant="tonal"
                                        color="primary">Abbruch</v-btn>
                                </v-col>
                                <v-col cols="6">
                                    <v-btn block prepend-icon="mdi-content-save" @click="handleSave" variant="tonal"
                                        color="success">Umbenennen</v-btn>
                                </v-col>

                            </v-row>
                        </v-form>
                    </v-card-actions>
                </v-card>

                <!-- NEUE HOMEPAGE -->
                <v-card title="Leere Homepage" color="primary" variant="tonal" text="Anlegen einer neuen Homepage"
                    v-if="action === 'new'">
                    <v-card-actions class="d-flex justify-end">
                        <v-btn prepend-icon="mdi-plus" @click="createHomepage" variant="tonal">Erstellen</v-btn>
                    </v-card-actions>
                </v-card>


            </v-col>
        </v-row>



    </v-container>
</template>
<script>
import { useValidationRulesSetup } from "@/helpers/rules";
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";

import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";

export default {
    setup() { return useValidationRulesSetup(); },

    components: { ItsMenuButton, ItsGridBox },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();
        await this.loadHomepages();
        this.homepage_copy = JSON.parse(JSON.stringify(this.homepage));




    },
    unmounted() { },

    data() {
        return {
            adminStore: null,
            navigationStore: null,
            homepageStore: null,
            action: "overview",
            action_2: "",
            is_valid: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["homepages", "homepage", "homepage_copy", "data"]),
    },

    methods: {

        abortRename() {
            this.homepage_copy = JSON.parse(JSON.stringify(this.homepage));
            this.action_2 = "";
        },

        selectHomepage(homepage) {
            this.homepage = homepage;
            this.homepage_copy = JSON.parse(JSON.stringify(this.homepage));
        },


        async handleSave() {
            await this.homepageStore.saveHomepage(this.homepage_copy);
            await this.loadHomepages();
            this.action_2 = "";
            this.homepage = JSON.parse(JSON.stringify(this.homepage_copy));

        },


        async handleDelete(id) {
            await this.homepageStore.deleteHomepage(id);
            this.action_2 = "";
            this.homepage = null;
            this.homepage_copy = null;
            await this.loadHomepages();

        },

        async loadHomepages() {
            await this.homepageStore.loadHomepages();
        },


        async createHomepage() {
            await this.homepageStore.createHomepage();
            await this.loadHomepages();
            this.homepage_copy = JSON.parse(JSON.stringify(this.homepage));
            this.action = "overview";
        },

        menuClick(action) {
            if (this.action === action) this.action = "overview"; else this.action = action;

        },
        runAction(methodName) {
            if (typeof this[methodName] === "function") {
                this[methodName]();
            }
        },
    },
};
</script>
