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
            <its-menu-button title="Auswahl" icon="mdi-arrow-left" color="secondary" to="/admin/homepage" />

            <its-menu-button title="Übersicht" icon="mdi-view-grid-outline"
                :color="action == 'overview' ? 'primary' : 'secondary'" @click="action = 'overview'" />



            <its-menu-button title="Basics" icon="mdi-cog" :color="action == 'basics' ? 'primary' : 'secondary'"
                @click="action = 'basics'" />

            <its-menu-button title="Startseite" icon="mdi-airplane-landing"
                :color="action == 'landing_page' ? 'primary' : 'secondary'" @click="action = 'landing_page'" />

            <its-menu-button title="Elemente" icon="mdi-page-layout-header"
                :color="action == 'elements' ? 'primary' : 'secondary'" @click="action = 'elements'" />

            <its-menu-button title="Inhalte" icon="mdi-video-stabilization"
                :color="action == 'contents' ? 'primary' : 'secondary'" @click="action = 'contents'" />

            <its-menu-button title="Seiten" icon="mdi-book-open-page-variant"
                :color="action == 'pages' ? 'primary' : 'secondary'" @click="action = 'pages'" />

            <its-menu-button title="Vorschau" icon="mdi-web" @click="action = 'preview'"
                :color="action == 'previéw' ? 'primary' : 'secondary'" />
        </v-row>



        <Basics :homepage="homepage" v-if="action == 'basics'" @abort="action = 'overview'"
            @save="action = 'overview'" />

        <LandingPage :homepage="homepage" v-if="action == 'landing_page'" @abort="action = 'overview'"
            @save="action = 'overview'" />

        <Elements :homepage="homepage" v-if="action == 'elements'" @abort="action = 'overview'"
            @save="action = 'overview'" />


    </v-container>
</template>

<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNavigationStore } from "@/stores/admin/NavigationStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import Basics from "./forms/Basics.vue";
import LandingPage from "./forms/LandingPage.vue";
import Elements from "./forms/Elements.vue";


import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";

export default {
    components: { ItsMenuButton, ItsGridBox, Basics, Elements, LandingPage },

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
