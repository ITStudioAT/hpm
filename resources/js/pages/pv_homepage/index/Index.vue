<template>
    <v-container fluid class="ma-0 w-100 pa-0">

        <Renderer :item="homepageStore.elementById(item.id)" v-for="(item, i) in main.parts" v-if="main" />

        <div style="height: 200vh;">
            Hello World! Here is the Index!
        </div>

    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../../stores/HomepageStore";
import Renderer from "../components/Renderer.vue";
export default {

    components: { Renderer },

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
        ...mapWritableState(useHomepageStore, ['homepage', 'main']),

    },

    methods: {

    }

}
</script>