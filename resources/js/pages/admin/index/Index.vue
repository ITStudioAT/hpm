<template>
    <v-container fluid class="ma-0 w-100 pa-2" v-if="hp">
        <!-- MENÜ -->
        <v-row class="d-flex flex-row ga-2 my-4 mt-0 w-100" no-gutters>
            <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                <template v-for="(item, i) in hp.items" :key="i">
                    <its-menu-button v-if="item.show_in_menu" :title="item.title" :icon="item.icon"
                        :color="active_element == 'homepage' ? 'primary' : 'secondary'" @click="activate(item.type)" />
                </template>
            </v-col>
        </v-row>
        <v-row class="my-4">

            <PVItem v-for="(item, i) in hp.items.filter(i => i.type === active_element)" :key="item.id || i"
                :item="item" :items="hp.items" @click-abort="abort" @click-save="saveHp(item)" />


            <!-- HOMEPAGE 
            <PVHomepage v-if="active_element == 'homepage'" :data="hp.homepage" @click-abort="abort"
                @click-save="saveHpm('App', data, data)" />
                -->

            <!-- KOPFZEILE
            <PVHeader v-if="active_element == 'header' && data.elements.header.component.value == 'Header'"
                :data="data.elements.header" @click-abort="abort" @click-save="saveHpm('App', data)" />
                -->
        </v-row>

        <!-- DEVICE PREVIEW         -->
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
import PVItem from "../pv_components/PVItem.vue";
import PVHomepage from "../pv_components/PVHomepage.vue";
import PVHeader from "../pv_components/PVHeader.vue";
export default {

    components: { ItsMenuButton, DevicePreview, ColBox, PVHomepage, PVHeader, PVItem },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.homepageStore = useHomepageStore();
        await this.adminStore.getHpm('App');
        this.hp = await this.adminStore.getJson('preview/hp');
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
        ...mapWritableState(useAdminStore, ['hpm', 'hp']),

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

        async saveHp(item) {
            const index = this.hp.items.findIndex(i => i.id === item.id);
            if (index !== -1) {
                // Update existing item
                this.hp.items.splice(index, 1, item);
            } else {
                // Insert if it was removed
                this.hp.items.push(item);
            }

            this.adminStore.saveJson('preview/hp', this.hp);

            console.log(this.hp);
            this.abort();
        },

        async saveHpm(source, data) {
            this.adminStore.saveHpm(source, data);
            this.abort();
        }

    }

}
</script>