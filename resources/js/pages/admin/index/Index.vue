<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100" no-gutters>
            <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                <its-menu-button title="Einstellungen" icon="mdi-cog" to="/hpm/admin" />
            </v-row>
            <DevicePreview></DevicePreview>
        </v-row>
    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../../stores/HomepageStore";
import ItsMenuButton from "../components/ItsMenuButton.vue";
import DevicePreview from "../components/DevicePreview.vue";

export default {

    components: { ItsMenuButton, DevicePreview },

    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },

    unmounted() {
    },

    data() {
        return {
            homepageStore: null,
            is_more_content: false,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error']),

    },

    methods: {

    }

}
</script>