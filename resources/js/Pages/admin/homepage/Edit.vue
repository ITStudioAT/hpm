<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100 mb-2" no-gutters v-if="homepage">
            <v-col>
                <div class="bg-primary pa-2 text-h5">
                    {{ homepage.name }}
                </div>
            </v-col>
        </v-row>

        <!-- Menüleiste oben -->
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button title="Zurück" icon="mdi-arrow-left" color="secondary" to="/admin/homepage" />

            <its-menu-button title="Übersicht" icon="mdi-view-grid-outline"
                :color="action == 'overview' ? 'primary' : 'secondary'" @click="action = 'overview'" />

            <its-menu-button title="Basics" icon="mdi-cog" :color="action == 'basics' ? 'primary' : 'secondary'"
                @click="action = 'basics'" />

            <its-menu-button title="Kopfzeile" icon="mdi-page-layout-header"
                :color="action == 'header' ? 'primary' : 'secondary'" @click="action = 'header'" />

            <its-menu-button title="Inhalte" icon="mdi-page-layout-body"
                :color="action == 'main' ? 'primary' : 'secondary'" @click="action = 'main'" />

            <its-menu-button title="Fußzeile" icon="mdi-page-layout-footer"
                :color="action == 'footer' ? 'primary' : 'secondary'" @click="action = 'footer'" />
        </v-row>

        <Basics :homepage="homepage" v-if="action == 'basics'" @abort="action = 'overview'" @save="action = 'overview'">
        </Basics>

        <v-sheet width="1280" height="1024" color="accent">
            <div class="text-h5">Preview</div>
            <div>DATA:</div>
            <div>{{ data }}</div>
            <div>HOMEPAGE:</div>
            <div>{{ homepage }}</div>
        </v-sheet>

        <v-row class="w-100" no-gutters>
            <v-col cols="12" md="6" lg="4" xl="3">HALLO </v-col>
            <v-col cols="12" md="6" lg="4" xl="3"> {{ homepage }} </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNavigationStore } from "@/stores/admin/NavigationStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import Basics from "./forms/Basics.vue";

import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";

export default {
    components: { ItsMenuButton, ItsGridBox, Basics },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.navigationStore = useNavigationStore();
        this.homepageStore = useHomepageStore();

        const id = this.$route.query.id;
        await this.homepageStore.loadHomepage(id);
    },
    unmounted() { },

    data() {
        return {
            adminStore: null,
            navigationStore: null,
            homepageStore: null,
            action: "overview",
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["homepage", "data"]),
    },

    methods: {
        runAction(methodName) {
            if (typeof this[methodName] === "function") {
                this[methodName]();
            }
        },
    },
};
</script>
