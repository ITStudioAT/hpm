<template>
    <!-- OVERVIEW HOMEPAGES -->
    <v-card tile flat :disabled="selected_action != ''">
        <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
            <its-menu-button
                title="Neue Homepage"
                icon="mdi-plus-thick"
                :color="selected_action == 'new_homepage' ? 'primary' : 'secondary'"
                @click="newHomepage" />

            <its-menu-button
                title="Umbenennen"
                icon="mdi-pencil"
                :color="selected_action == 'rename_homepage' ? 'primary' : 'secondary'"
                @click="renameHomepage(active_homepage)"
                v-if="active_homepage" />

            <its-menu-button
                title="Nicht Löschen"
                icon="mdi-delete-off"
                color="success"
                @click="delete_action = 0"
                v-if="active_homepage && delete_action == 1" />

            <its-menu-button
                title="Löschen"
                icon="mdi-delete"
                :color="delete_action > 0 ? 'error' : 'warning'"
                @click="delete_action == 0 ? delete_action++ : doDelete(active_homepage)"
                v-if="active_homepage" />
        </v-row>
    </v-card>

    <!-- LIST OF HOMEPAGES -->
    <ListOfHomepages
        :homepages="homepages"
        :active_homepage="active_homepage"
        @newActiveHomepage="active_homepage = $event"
        v-if="selected_action == ''" />

    <!-- NEUE/EDIT HOMEPAGE -->
    <Overview_NewEditHomepage
        :data="data"
        v-if="['new_homepage', 'rename_homepage'].includes(selected_action)"
        @save="doSave($event)"
        @abort="selected_action = ''" />
</template>
<script>
import { mapWritableState } from 'pinia'
import { useHomepageStore } from '@/stores/admin/HomepageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import Overview_NewEditHomepage from './Overview_NewEditHomepage.vue'
import ListOfHomepages from './ListOfHomepages.vue'

export default {
    components: { ItsMenuButton, Overview_NewEditHomepage, ListOfHomepages },

    async beforeMount() {
        this.homepageStore = useHomepageStore()
        this.doOverview()
    },

    unmounted() {},

    data() {
        return {
            hompageStore: null,
            data: {},
        }
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['active_homepage', 'homepages', 'delete_action', 'selected_action']),
    },

    methods: {
        async doOverview() {
            await this.homepageStore.index()
            // Preselect first homepage if none is active
            if (!this.active_homepage && this.homepages?.length) {
                this.active_homepage = this.homepages[0]
            }
            this.delete_action = 0
            this.selected_action = ''
        },

        async doDelete(homepage) {
            await this.homepageStore.delete(homepage)
            await this.homepageStore.index()
            this.active_homepage = null
            this.delete_action = 0
        },
        async doSave(data) {
            this.data = {}
            this.selected_action = ''
        },

        newHomepage() {
            this.data = {}
            this.selected_action = 'new_homepage'
        },

        renameHomepage(homepage) {
            this.data = homepage
            this.selected_action = 'rename_homepage'
        },
    },
}
</script>
