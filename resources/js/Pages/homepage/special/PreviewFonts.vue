<template>

    <v-layout full-height class="hpm-c-all" v-if="is_ready">
        <v-container class=" d-flex justify-center hpm-c-container pa-0">

            <v-card flat tile width="1024" min-height="600">

                <v-card-text>

                    <div class="heroTitle">Mehr Wirkung, weniger Aufwand</div>
                    <div class="heroLead mt-4">Unsere Lösung bündelt das Wesentliche und spart Ihnen genau dort Zeit, wo
                        es
                        wirklich zählt. Statt sich in endlosen Details und komplizierten Prozessen zu verlieren, starten
                        Sie
                        mit einem klaren, strukturierten Fundament. Schnell, unkompliziert und professionell.</div>

                    <div class="title mt-12">Mehr Wirkung, weniger Aufwand</div>
                    <div class="subtitle">So präsentieren Sie sich professionell - ohne komplizierte Tools.</div>
                    <div class="content mt-4">Unsere Lösung bündelt das Wesentliche und spart Ihnen genau dort Zeit, wo
                        es
                        wirklich zählt. Statt sich in endlosen Details und komplizierten Prozessen zu verlieren, starten
                        Sie
                        mit einem klaren, strukturierten Fundament. Schnell, unkompliziert und professionell.</div>

                </v-card-text>





            </v-card>

        </v-container>

    </v-layout>

</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

const declsToCss = (obj) => {
    return Object.entries(obj)
        .map(([prop, val]) => `${toKebab(prop)}:${val};`)
        .join('');
};

const toKebab = (s) => s.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();
export default {

    components: {},

    async beforeMount() {
        const fontset = this.$route.query.fontset;

        await this.homepageStore.fontset(fontset);
        this.applyFonts(fontset);

        this.is_ready = true;
    },

    mounted() {

    },

    data() {
        return {
            homepageStore: useHomepageStore(),
            colorset: null,
            colorDefs: null,
            target: 'root',
            is_ready: false,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error', 'fontset']),

    },

    watch: {

    },

    methods: {
        applyFonts(fontset) {

            this.fontset.fonts.forEach(font => {
                const style = document.createElement("style");
                style.innerHTML = this.fontset[font];
                document.head.appendChild(style);
            });

            let css = '';
            for (const [tokenName, rules] of Object.entries(this.fontset.styles)) {
                const className = `.${tokenName}`;
                css += `${className}{${declsToCss(rules)}}\n`;
            }


            const style = document.createElement("style");
            style.innerHTML = css;
            document.head.appendChild(style);
        }


    }

}
</script>
<style scoped></style>