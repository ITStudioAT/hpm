<template>
    <v-container fluid class="h-100 w-100 d-flex align-center justify-center">
        HOMEPAGE {{ is_loading }}
    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";
export default {

    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore();
        this.preview_id = this.$route.query.preview_id;
        this.homepageStore.loadHomepage(7);
    },

    unmounted() {
    },

    data() {
        return {
            homepageStore: null,
            preview_id: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error']),

    },

    methods: {

    }

}
</script>