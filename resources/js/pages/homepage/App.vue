<template>

    <v-layout ref="app" class="d-flex flex-column" style="min-height: 100vh;" v-if="homepage && hpm.is_active">
        <!-- App Bar -->

        <!-- <div class="w-100 bg-green">Hello World</div> -->
        <Header :homepageStore="homepageStore" v-if="hpm.elements.header.is_active.value" />

        <!-- Main Content -->
        <v-main name="main" class="flex-grow-1 bg-background" v-if="hpm.elements.main.is_active.value">
            <router-view />
        </v-main>

        <!-- Footer  -->
        <div class="flex-shrink-1">
            <Footer :homepageStore="homepageStore" v-if="hpm.elements.footer.is_active.value" />
        </div>

        <!-- Es wird aktuell etwas geladen-->
        <div class="d-flex justify-center align-center"
            style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); z-index: 9999;"
            v-if="is_loading > 0">
            <v-progress-circular indeterminate size="70" width="7" />
        </div>

    </v-layout>

</template>


<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../stores/HomepageStore";
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";

export default {

    components: { Header, Footer },
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
            hpm: {
                name: 'Homepage-Struktur',
                type: 'homepage',
                component: 'App',
                is_active: {
                    value: true, name: 'Aktiv', options_type: { type: 'boolean' },
                    options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                },
                elements: {
                    header: {
                        is_active: {
                            value: true, name: 'Kopfbereich', options_type: { type: 'boolean' },
                            options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                        },

                    },
                    main: {
                        is_active: {
                            value: false, name: 'Hauptbereich', options_type: { type: 'boolean' },
                            options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                        },
                    },
                    footer: {
                        is_active: {
                            value: false, name: 'Hauptbereich', options_type: { type: 'boolean' },
                            options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                        },
                    },

                },
            },

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
