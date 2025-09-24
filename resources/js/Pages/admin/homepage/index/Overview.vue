<template>

    <!-- OVERVIEW HOMEPAGES -->
    <v-card tile flat :disabled="selected_action != ''">
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button title="Neue Homepage" icon="mdi-plus-thick"
                :color="selected_action == 'new_homepage' ? 'primary' : 'secondary'"
                @click="newHomepage" />

            <its-menu-button title="Umbenennen" icon="mdi-pencil"
                :color="selected_action == 'rename_homepage' ? 'primary' : 'secondary'"
                @click="renameHomepage(active_homepage)" v-if="active_homepage" />
        </v-row>
    </v-card>


    <!-- NEUE HOMEPAGE -->
    <Overview_NewHomepage :data="data" v-if="['new_homepage','rename_homepage'].includes(selected_action)" @save="doSave($event)"
        @abort="selected_action = ''" />

</template>
<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import Overview_NewHomepage from "./Overview_NewHomepage.vue";
import { registerRuntimeCompiler } from "vue";


export default {


    components: { ItsMenuButton, Overview_NewHomepage },

    async beforeMount() {
        this.adminStore = useAdminStore(); this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();
    },

    unmounted() {
    },

    data() {
        return {
            adminStore: null,
            hompageStore: null,
            selected_action: '',
            data: {}


        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['active_homepage','homepages']),
    },

    methods: {

        async doSave(data) {
            let answer = false;
            if (data.id) answer=await this.homepageStore.update(data);
            else answer=await this.homepageStore.store(data)

            if (!answer) return;

            await await this.homepageStore.index();
                        
            
            
            this.data = {};
            this.selected_action = '';
        },

        newHomepage() {
            this.data = {};
            this.selected_action = 'new_homepage'
        },

        renameHomepage(homepage) {
            this.data = homepage;
            this.selected_action = 'rename_homepage'
        }


    }

}
</script>
