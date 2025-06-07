<template>

    <v-layout ref="app" class="d-flex flex-column" style="min-height: 100vh;" v-if="homepage && hpm.is_active">
        <!-- App Bar -->

        <!-- <div class="w-100 bg-green">Hello World</div> -->
        <component :is="hpm.elements.header.component.value" :homepageStore="homepageStore" :hpm="hpm.elements.header"
            v-if="hpm.elements.header.is_active.value" />

        <!-- Main Content -->
        <v-main name="main" class="flex-grow-1 bg-background" v-if="hpm.elements.main.is_active.value">
            <router-view />
        </v-main>

        <!-- Footer  -->
        <div class="flex-shrink-1">
            <Footer :homepageStore="homepageStore" v-if="hpm.elements.footer.is_active.value" />
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
                name: "Homepage-Struktur",
                type: "homepage",
                component: "App",
                is_active: {
                    value: true,

                },
                elements: {
                    header: {
                        component: { value: 'Header' },
                        is_active: { value: true },
                        is_fluid: { value: false },
                        max_width: { value: '1280px' },
                        bg_color: { value: 'bg-primary' },
                        color: { value: 'text-white' },
                        density: { value: 'prominent' },
                        is_flat: { value: false },
                        is_tile: { value: false },
                        scroll_behavior: { value: 'hide' },

                        elements: {
                            topline: {
                                component: { value: 'Topline' },
                                is_active: { value: true },
                                height: { value: '30px' },
                                columns: { value: 1 },
                                bg_color: { value: 'bg-white' },
                                elements: {
                                    col_1: {
                                        component: { value: 'ShortText' },
                                        color: { value: 'text-black' },
                                        bg_color: { value: '' },
                                        size: { value: 'text-body-2' },
                                        font: { value: 'font-weight-medium ' },
                                        justify: { value: 'justify-start' },
                                        text: { value: 'Herzlich Wollkommen!' },
                                        padding: { value: 'px-2' }
                                    },
                                    col_2: {
                                        component: { value: 'ShortText' },
                                        color: { value: 'text-black' },
                                        bg_color: { value: '' },
                                        size: { value: 'text-body-2' },
                                        font: { value: 'font-weight-medium ' },
                                        justify: { value: 'justify-start' },
                                        text: { value: 'Herzlich Wollkommen!' },
                                        padding: { value: 'px-2' }
                                    },
                                    col_3: {
                                        component: { value: 'ShortText' },
                                        color: { value: 'text-black' },
                                        bg_color: { value: '' },
                                        size: { value: 'text-body-2' },
                                        font: { value: 'font-weight-medium ' },
                                        justify: { value: 'justify-start' },
                                        text: { value: 'Herzlich Wollkommen!' },
                                        padding: { value: 'px-2' }
                                    }
                                }


                            },
                            appbar_left: {

                            },
                            appbar_center: {

                            },
                            appbar_right: {

                            },

                        }
                    },
                    main: {
                        component: { value: 'Main' },
                        is_active: { value: false },
                    },
                    footer: {
                        component: { value: 'Footer' },
                        is_active: { value: false },
                    }
                }
            },
            homepageStore: null
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'homepage', 'main']),



    },

    methods: {

    }

}
</script>
