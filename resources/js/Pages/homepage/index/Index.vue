<template>
    <v-layout ref="app" min-height="100vh">
        <!--
        <v-app-bar color="hightlight" name="app-bar">

        </v-app-bar>
        -->




        <v-main class="background">
            <v-container class="h-100 d-flex flex-column align-center justify-center main">
                <div>Text im Main</div>
                <div class="heroTitle">HeroTitle</div>
                <div class="heroLead">HeroLead</div>

                <div class="first">
                    <div class="heroTitle">HeroTitle bg-first</div>
                    <div class="heroLead">HeroLead bg-first</div>
                </div>

                <div class="second">
                    <div class="heroTitle">HeroTitle bg-second</div>
                    <div class="heroLead">HeroLead bg-second</div>
                </div>

                <div class="third">
                    <div class="heroTitle">HeroTitle bg-second</div>
                    <div class="heroLead">HeroLead bg-second</div>
                </div>



            </v-container>
        </v-main>
        <!--
        <v-footer color="surface-light" name="footer" app>
            Footer
        </v-footer>
        -->
    </v-layout>
</template>

<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {
    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },

    data() {
        return {
            homepageStore: null,
        };
    },

    computed: {
        // Kept for debugging; typography comes from CSS classes (.heroTitle, .content, ...)
        ...mapWritableState(useHomepageStore, ["homepage"]),
    },
};
</script>
