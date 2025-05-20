<template>


    <v-layout ref="app" class="d-flex flex-column" style="min-height: 100vh;">

        <!-- Navigation -->
        <v-navigation-drawer v-model="show_navigation_drawer" color="primary">
            <v-toolbar color="appbar">
                <v-toolbar-title>
                    <div class="text-body-2">HPMaker</div>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon="mdi-menu-close" @click="show_navigation_drawer = false" v-if="show_navigation_drawer" />
            </v-toolbar>
            <v-list>
                <v-list-item :exact="false" prepend-icon="mdi-home" title="Homepage" to="/hpm/admin" />
            </v-list>
        </v-navigation-drawer>


        <!-- App Bar -->
        <v-app-bar name="app-bar" color="primary" flat>
            <template v-slot:prepend>
                <v-btn icon="mdi-menu-open" v-if="!show_navigation_drawer" @click="show_navigation_drawer = true" />
                <v-img :src="'/storage/images/logo.png'" alt="Logo" width="32" class="pl-2"></v-img>
            </template>

            <v-container class="d-flex justify-space-between align-center h-100">
                <div class="text-body-2">HPMaker</div>
            </v-container>
        </v-app-bar>

        <!-- Main Content -->
        <v-main name="main" class="flex-grow-1 bg-background">
            <router-view />
        </v-main>

        <!-- Footer -->
        <v-footer name="footer" tile color="primary" app>
            <v-container class="text-center">
                © {{ new Date().getFullYear() }} MyApp. All rights reserved.
            </v-container>
        </v-footer>
    </v-layout>






</template>


<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../stores/HomepageStore";

export default {

    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore(); this.homepageStore.initialize(this.$router);
        // this.homepageStore.loadConfig();
    },

    unmounted() {
    },

    data() {
        return {
            show_navigation_drawer: true,
            homepageStore: null,
            homepage: {
                header: {
                    is_active: true,
                    bg_color: 'header',
                    color: 'white',
                    density: 'default',
                    is_flat: true,
                    is_tile: true,
                    srcoll_behavior: 'hide',
                },
                footer: {
                    is_active: true,
                    is_tile: false,
                    bg_color: 'footer',
                    color: 'yellow',
                },
            }
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error']),

    },

    methods: {

    }

}
</script>
