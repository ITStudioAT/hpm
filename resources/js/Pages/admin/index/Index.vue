<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- Menüleiste oben -->
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                :title="item.title"
                :subtitle="item.subtitle"
                :icon="item.icon"
                :to="item.to"
                :color="item.color"
                @click="runAction(item.action)"
                v-for="(item, i) in navigationStore.menu"
            />
        </v-row>

        <v-row class="w-100" no-gutters>
            <v-col cols="12" md="6" lg="4" xl="3">
                <its-grid-box
                    color="primary"
                    title="Homepages"
                    class="h-100 w-100"
                >
                    <v-list density="compact" class="text-body-2">
                        <v-list-item density="compact">
                            <v-btn
                                prepend-icon="mdi-plus"
                                block
                                flat
                                tile
                                color="primary"
                                >Neu</v-btn
                            >
                        </v-list-item>

                        <v-list-item density="compact">
                            <v-btn
                                prepend-icon="mdi-content-copy"
                                block
                                flat
                                tile
                                color="primary"
                                >Kopie von aktiver</v-btn
                            >
                        </v-list-item>
                    </v-list>
                </its-grid-box>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNavigationStore } from "@/stores/admin/NavigationStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import ItsGridBox from "@/pages/components/ItsGridBox.vue";

export default {
    components: { ItsMenuButton, ItsGridBox },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.navigationStore = useNavigationStore();
        await this.navigationStore.loadMenu("home");
    },

    unmounted() {},

    data() {
        return {
            adminStore: null,
            navigationStore: null,
        };
    },

    computed: {},

    methods: {
        runAction(methodName) {
            if (typeof this[methodName] === "function") {
                this[methodName]();
            }
        },
    },
};
</script>
