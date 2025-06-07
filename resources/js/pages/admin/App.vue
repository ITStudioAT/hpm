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
                <v-list-item :exact="false" prepend-icon="mdi-shield-crown-outline" title="Admin" to="/admin" />
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
        <v-footer name="footer" tile color="primary" app v-if="config">
            <v-container class="text-center">
                © {{ new Date().getFullYear() }} HPMaker. All rights reserved. {{ config.version }}
            </v-container>
        </v-footer>

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
import { useAdminStore } from "../../stores/AdminStore";

export default {

    components: {},

    async beforeMount() {
        this.adminStore = useAdminStore(); this.adminStore.initialize(this.$router);
        this.adminStore.getConfig();
    },

    unmounted() {
    },

    data() {
        return {
            show_navigation_drawer: true,
            adminStore: null,

        };
    },

    computed: {
        ...mapWritableState(useAdminStore, ['config', 'is_loading', 'error']),

    },

    methods: {

    }

}
</script>
