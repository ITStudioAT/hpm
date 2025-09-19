<template>

    <v-app>
        <!-- Alle Dinge sind geladen -->

        <v-main v-if="is_ready">

            <router-view></router-view>
            <ItsNotification />

        </v-main>




        <!-- Es wird aktuell etwas geladen-->
        <v-container class="d-flex justify-center align-center" style="height: 100vh;" v-if="is_loading > 0">
            <v-progress-circular indeterminate size="70" width="7"></v-progress-circular>
        </v-container>

    </v-app>



</template>

<script setup>
import ItsNotification from "@/pages/components/ItsNotification.vue";
</script>

<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {

    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore(); this.homepageStore.initialize(this.$router);
        this.is_ready = true
    },

    unmounted() {
    },

    data() {
        return {
            homepageStore: null,
            is_ready: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['is_loading', 'error']),

    },

    methods: {

    }

}
</script>
