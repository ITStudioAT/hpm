<template>


    <v-layout ref="app" class="d-flex flex-column" style="min-height: 100vh;" v-if="homepage">
        <!-- App Bar -->
        <Renderer :item="homepageStore.elementById(homepage[0].parts.header.id)" />

        <!-- Main Content -->
        <v-main name="main" class="flex-grow-1 bg-background">
            <router-view />
        </v-main>

        <!-- Footer  -->
        <div class="flex-shrink-1">
            <Renderer :item="homepageStore.elementById(homepage[0].parts.footer.id)" />
        </div>

    </v-layout>






</template>


<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../stores/HomepageStore";
import Renderer from "./components/Renderer.vue";

export default {

    components: { Renderer },
    async beforeMount() {
        this.homepageStore = useHomepageStore(); this.homepageStore.initialize(this.$router);
        await this.homepageStore.getConfig();
        await this.homepageStore.loadHomepage();
        this.main = this.homepageStore.elementById(this.homepage[0].parts.main.id);

    },

    unmounted() {
    },

    data() {
        return {
            homepageStore: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'homepage', 'main']),



    },

    methods: {

    }

}
</script>
