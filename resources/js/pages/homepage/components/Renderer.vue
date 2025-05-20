<template>
    <AppBar :item="item" :homepageStore="homepageStore" v-if="item?.type == 'homepage_header'" />
    <HeaderImageText :item="item" v-if="item?.type == 'header_image&text'" />
    <ShortText :item="item" v-if="item?.type == 'text'" />
    <Sandwich :item="item" v-if="item?.type == 'sandwich'" />
    <SimpleMenu :item="item" v-if="item?.type == 'simple_menu'" />
    <Footer :item="item" :homepageStore="homepageStore" v-if="item?.type == 'homepage_footer'" />
    <HeroText :item="item" :homepageStore="homepageStore" v-if="item?.type == 'hero_text'" />

</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "../../../stores/HomepageStore";
import AppBar from "./AppBar.vue";
import HeaderImageText from "./HeaderImageText.vue";
import Sandwich from "./Sandwich.vue";
import ShortText from "./ShortText.vue";
import SimpleMenu from "./SimpleMenu.vue";
import Footer from "./Footer.vue";
import HeroText from "./HeroText.vue";
export default {
    props: ['item'],

    components: { AppBar, HeaderImageText, Sandwich, ShortText, SimpleMenu, Footer, HeroText },

    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },

    unmounted() {
    },

    data() {
        return {
            homepageStore: null,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['homepage']),
    },

    methods: {

    }

}
</script>