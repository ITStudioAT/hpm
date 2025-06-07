<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- MENÜ -->
        <v-row class="d-flex flex-row ga-2 my-4 mt-0 w-100" no-gutters>
            <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                <its-menu-button title="Homepage" icon="mdi-home"
                    :color="active_element == 'homepage' ? 'primary' : 'secondary'" @click="activate('homepage')" />
                <its-menu-button title="Kopfbereich" icon="mdi-page-layout-header"
                    :color="active_element == 'header' ? 'primary' : 'secondary'" @click="activate('header')" />
                <its-menu-button title="Hauptbereich" icon="mdi-application-settings-outline"
                    :color="active_element == 'main' ? 'primary' : 'secondary'" @click="activate('main')" />
                <its-menu-button title="Fußbereich" icon="mdi-page-layout-footer"
                    :color="active_element == 'footer' ? 'primary' : 'secondary'" @click="activate('footer')" />
            </v-col>
        </v-row>

        <v-row class="my-4">

            <!-- HOMEPAGE -->
            <PVHomepage v-if="active_element == 'homepage'" :data="data" @click-abort="abort"
                @click-save="saveHpm('App', data, data)" />

            <!-- KOPFZEILE-->
            <PVHeader v-if="active_element == 'header' && data.elements.header.component.value == 'Header'"
                :data="data.elements.header" @click-abort="abort" @click-save="saveHpm('App', data)" />
        </v-row>

        <!-- DEVICE PREVIEW -->
        <v-row no-gutters class=" my-4">
            <v-col cols="12">
                <DevicePreview></DevicePreview>
            </v-col>
        </v-row>



    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "../../../stores/AdminStore";
import { useHomepageStore } from "../../../stores/HomepageStore";
import ItsMenuButton from "../components/ItsMenuButton.vue";
import ColBox from "../components/ColBox.vue";
import DevicePreview from "../components/DevicePreview.vue";

import PVHomepage from "../pv_components/PVHomepage.vue";
import PVHeader from "../pv_components/PVHeader.vue";
export default {

    components: { ItsMenuButton, DevicePreview, ColBox, PVHomepage, PVHeader },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.homepageStore = useHomepageStore();
        await this.adminStore.getHpm('App');
    },

    unmounted() {
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            active_element: null,
            data: {},

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error']),
        ...mapWritableState(useAdminStore, ['hpm']),

    },

    methods: {

        abort() {
            this.active_element = null;
        },

        activate(element) {
            if (this.active_element == element) {
                this.active_element = null;
            } else {
                this.active_element = element;
                this.data = JSON.parse(JSON.stringify(this.hpm));
            }
        },

        async getHpm(source) {
            this.adminStore.getHpm(source);
        },

        async saveHpm(source, data) {
            this.adminStore.saveHpm(source, data);
            this.abort();
        }

    }

}
</script>