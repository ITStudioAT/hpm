<template>
  <v-container fluid class="ma-0 w-100 pa-2">
    <!-- Aktive Homepage -->
    <v-row
      v-if="active_homepage"
      class="d-flex flex-row ga-2 mb-2 mt-0 w-100"
      no-gutters
    >
      <v-col>
        <v-card tile flat color="accent">
          <v-card-title class="d-flex flex-row ga-2 justify-space-between">
            <div>
              <v-btn flat tile color="primary" variant="tonal">
                <v-icon icon="mdi-cog" />
              </v-btn>
              Aktive Homepage: {{ active_homepage.name }}
            </div>
          </v-card-title>
        </v-card>
      </v-col>
    </v-row>

    <!-- HAUPTMENÜ --> 
     <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters> 
        <its-menu-button title="Übersicht" icon="mdi-view-agenda" :color="selected_menu == 'overview' ? 'primary' : 'secondary'" /> 
    </v-row> 
    
    <!-- OVERVIEW --> 
     <Overview v-if="selected_menu = 'overview'" />

    <!-- HOMEPAGES -->
     <ListOfHomepages :homepages="homepages" :active_homepage="active_homepage" @newActiveHomepage="active_homepage =  $event"/>
    
  </v-container>
</template>

<script>
import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import Overview from "./index/Overview.vue";
import ListOfHomepages from "./index/ListOfHomepages.vue";

export default {
  components: { ItsMenuButton, Overview,ListOfHomepages },

  async beforeMount() {
    this.adminStore = useAdminStore();
    this.adminStore.initialize(this.$router);
    this.homepageStore = useHomepageStore();
    await this.homepageStore.index();

    // Preselect first homepage if none is active
    if (!this.active_homepage && this.homepages?.length) {
      this.active_homepage = this.homepages[0];
    }
  },

  data() {
    return {
      adminStore: null,
      homepageStore: null,
      selected_menu: "overview",
    };
  },

  computed: {
    ...mapWritableState(useAdminStore, [
      "config",
      "is_loading",
      "show_navigation_drawer",
      "load_config",
      "user_roles",
    ]),
    ...mapWritableState(useHomepageStore, ["active_homepage", "homepages"]),
  },

  methods: {

  },
};
</script>
