<template>

    <v-layout full-height class="hpm-c-all" v-if="is_ready">
        <v-container class="d-flex flex-column  align-center hpm-c-container pa-0">
            <v-card flat tile class="w-100">
                <v-toolbar flat tile class="hpm-c-appbar pa-2 text-caption">
                    <div class="d-block">
                        <div class="content font-weight-medium">Kopfleiste Standard</div>
                    </div>
                </v-toolbar>
            </v-card>

            <div>
                {{ cf_sets }}
            </div>

            <v-card flat tile max-width="1024" min-height="600">

                <div class="d-flex flex-row w-100">

                    <!-- VARIANTE STANDARD-->
                    <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-0">
                        <v-card-text class="pt-16">

                            <div class="d-block">
                                <div class="text-body-2 font-weight-medium">Textgrößen: heroTitle heroLead</div>
                                <div>Hintergrundfarbe: Farbe-0</div>
                                <div>Schriftfarbe: Text-0</div>
                            </div>

                            <div class="heroTitle hpm-hl mt-2">Mehr Wirkung, weniger Aufwand</div>
                            <div class="heroLead hpm-hl mt-2">Unsere Lösung bündelt das Wesentliche und spart Ihnen
                                genau dort Zeit, wo es wirklich zählt. Statt sich in endlosen Details und
                                komplizierten Prozessen zu verlieren, starten Sie mit einem klaren, strukturierten
                                Fundament. Schnell, unkompliziert und professionell.</div>

                        </v-card-text>
                    </v-card>

                </div>

                <div class="d-flex flex-row w-100 mt-6">
                    <v-row>
                        <!-- Bereich mit Farbe 1-->
                        <v-col cols="12" md="6">
                            <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-1">
                                <v-card-text class="pt-16">

                                    <div class="d-block">
                                        <div class="text-body-2 font-weight-medium">Textgrößen: title content
                                            footnote
                                        </div>
                                        <div>Hintergrundfarbe: Farbe-1</div>
                                        <div>Schriftfarbe: Text-0</div>
                                    </div>

                                    <div class="title hpm-hl mt-2">Mehr Wirkung, weniger Aufwand</div>
                                    <div class="subtitle">So präsentieren Sie sich professionell - ohne komplizierte
                                        Tools.</div>
                                    <div class="content mt-2">Unsere Lösung bündelt das Wesentliche und spart
                                        Ihnen
                                        genau dort Zeit, wo es wirklich zählt. Statt sich in endlosen Details und
                                        komplizierten Prozessen zu verlieren, starten Sie mit einem klaren,
                                        strukturierten
                                        Fundament. Schnell, unkompliziert und professionell.</div>
                                    <div class="border-b border-opacity mt-4" />
                                    <div class="footnote mt-2">„Schneller ans Ziel: Mit einem klaren Fundament und
                                        ohne
                                        Umwege sparen Sie Zeit, Energie und Nerven bei jedem Schritt.“</div>

                                </v-card-text>
                            </v-card>
                        </v-col>

                        <!-- Bereich mit Farbe 2-->
                        <v-col cols="12" md="6">
                            <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-2">
                                <v-card-text class="pt-16">

                                    <div class="d-block">
                                        <div class="text-body-2 font-weight-medium">Textgrößen: title content
                                            footnote
                                        </div>
                                        <div>Hintergrundfarbe: Farbe-2</div>
                                        <div>Schriftfarbe: Text-0</div>
                                    </div>

                                    <div class="title hpm-hl mt-2">Mehr Wirkung, weniger Aufwand</div>
                                    <div class="subtitle">So präsentieren Sie sich professionell - ohne komplizierte
                                        Tools.</div>
                                    <div class="content mt-2">Unsere Lösung bündelt das Wesentliche und spart
                                        Ihnen
                                        genau dort Zeit, wo es wirklich zählt. Statt sich in endlosen Details und
                                        komplizierten Prozessen zu verlieren, starten Sie mit einem klaren,
                                        strukturierten
                                        Fundament. Schnell, unkompliziert und professionell.</div>
                                    <div class="border-b border-opacity mt-4" />
                                    <div class="footnote mt-2">„Schneller ans Ziel: Mit einem klaren Fundament und
                                        ohne
                                        Umwege sparen Sie Zeit, Energie und Nerven bei jedem Schritt.“</div>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <!-- Bereich mit Farbe 3-->
                        <v-col cols="12" md="6">
                            <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-3">
                                <v-card-text class="pt-16">

                                    <div class="d-block">
                                        <div class="text-body-2 font-weight-medium">Textgrößen: title content
                                            footnote
                                        </div>
                                        <div>Hintergrundfarbe: Farbe-3</div>
                                        <div>Schriftfarbe: Text-0</div>
                                    </div>

                                    <div class="title hpm-hl mt-2">Mehr Wirkung, weniger Aufwand</div>
                                    <div class="subtitle">So präsentieren Sie sich professionell - ohne komplizierte
                                        Tools.</div>
                                    <div class="content mt-2">Unsere Lösung bündelt das Wesentliche und spart
                                        Ihnen
                                        genau dort Zeit, wo es wirklich zählt. Statt sich in endlosen Details und
                                        komplizierten Prozessen zu verlieren, starten Sie mit einem klaren,
                                        strukturierten
                                        Fundament. Schnell, unkompliziert und professionell.</div>
                                    <div class="border-b border-opacity mt-4" />
                                    <div class="footnote mt-2">„Schneller ans Ziel: Mit einem klaren Fundament und
                                        ohne
                                        Umwege sparen Sie Zeit, Energie und Nerven bei jedem Schritt.“</div>

                                </v-card-text>
                            </v-card>
                        </v-col>


                        <!-- Bereich mit Farbe 0-->
                        <v-col cols="12" md="6">
                            <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-0">
                                <v-card-text class="pt-16">

                                    <div class="d-block">
                                        <div class="text-body-2 font-weight-medium">Textgrößen: title content
                                            footnote
                                        </div>
                                        <div>Hintergrundfarbe: Farbe-0</div>
                                        <div>Schriftfarbe: Text-0</div>
                                    </div>

                                    <div class="title hpm-hl mt-2">Mehr Wirkung, weniger Aufwand</div>
                                    <div class="subtitle">So präsentieren Sie sich professionell - ohne komplizierte
                                        Tools.</div>
                                    <div class="content mt-2">Unsere Lösung bündelt das Wesentliche und spart
                                        Ihnen
                                        genau dort Zeit, wo es wirklich zählt. Statt sich in endlosen Details und
                                        komplizierten Prozessen zu verlieren, starten Sie mit einem klaren,
                                        strukturierten
                                        Fundament. Schnell, unkompliziert und professionell.</div>
                                    <div class="border-b border-opacity mt-4" />
                                    <div class="footnote mt-2">„Schneller ans Ziel: Mit einem klaren Fundament und
                                        ohne
                                        Umwege sparen Sie Zeit, Energie und Nerven bei jedem Schritt.“</div>

                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </div>




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
        const colorset = this.$route.query.colorset;
        this.homepageStore = useHomepageStore();
        await this.homepageStore.colorset(colorset);
        this.applyCssVars(this.normalized);

        const fontset = this.$route.query.fontset;
        await this.homepageStore.fontset(fontset);
        this.applyFonts(fontset);

        await this.homepageStore.loadSets();

        this.is_ready = true;
    },

    mounted() {

    },

    data() {
        return {
            homepageStore: null,
            colorset: null,
            target: 'root',
            is_ready: false,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error', 'colorDefs', 'fontset', 'cf_sets']),
        normalized() {
            if (Array.isArray(this.colorDefs)) return Object.fromEntries(this.colorDefs)
            return this.colorDefs || {}
        }

    },

    watch: {
        colorDefs: {
            deep: true,
            immediate: false,     // set to true if you want first run here too
            handler() {
                this.applyCssVars(this.normalized)
            }
        }
    },

    methods: {
        applyCssVars(colorDefs) {
            // SSR guard
            if (typeof document === 'undefined') return

            const el = this.target === 'root'
                ? document.documentElement
                : document.querySelector(this.target)

            if (!el) return

            for (const [key, val] of Object.entries(this.colorDefs)) {
                const name = key.startsWith('--') ? key : `--${key}`
                el.style.setProperty(name, String(val))
            }

        },

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

<style scoped>
/*
.hpm-c-all
Hintergrund und Farbe für den kompletten Hintergrund
Hintergrund:    --bg-all 
Text:           --text-all

.hpm-c-container
Hintergrund und Farbe für den Inhaltsbereich (Standardfarbe)
Hintergrund:    --color-0
Text:           --text-0

APPBAR
.hpm-c-appbar
Hintergrund:    --bg-all
Text:           --text-all

APPBAR - VARIANTEN
.hpm-c-appbar-0...3 
Hintergrund:    --color0...3
Text:           --text-0

APPBAR - VARIANTE
.hpm-c-appbar-4
Hintergrund:    --appbar-bg-4
Text:           --appbar-text-4

APPBAR - VARIANTE 
.hpm-c-appbar-5 
Hintergrund:    --appbar-bg-5 
Text:           --appbar-text-5

TEXTBLOCK MIT VARIANTEN
.hpm-c-0...3
Hintergrund:    --color-0...3
Text:           --text-0

HEADLINE
.hpm-hl
Text:           --text-hl

TEXTFARBE (HEADLINE) VARIANTEN
.hpm-text-0...3
Text:           --color-0...3

BUTTON MIT VARIANTEN
.hpm-c-btn-0...3
Hintergrund:    --color-0...3
Text:           --text-0


*/

.hpm-bg-all {
    background-color: var(--bg-all);
}


.hpm-c-all {
    background-color: var(--bg-all);
    color: var(--text-all);
}

.hpm-c-container {
    background-color: var(--color-0);
    color: var(--text-0);
}

.hpm-c-appbar {
    background-color: var(--bg-all);
    color: var(--text-all);
}

.hpm-c-appbar-0 {
    background-color: var(--color-0);
    color: var(--text-0);
}

.hpm-c-appbar-1 {
    background-color: var(--color-1);
    color: var(--text-0);
}

.hpm-c-appbar-2 {
    background-color: var(--color-2);
    color: var(--text-0);
}

.hpm-c-appbar-3 {
    background-color: var(--color-3);
    color: var(--text-0);
}

.hpm-c-appbar-4 {
    background-color: var(--appbar-bg-4);
    color: var(--appbar-text-4);
}

.hpm-c-appbar-5 {
    background-color: var(--appbar-bg-5);
    color: var(--appbar-text-5);
}

.hpm-c-0 {
    background-color: var(--color-0);
    color: var(--text-0);
}

.hpm-c-1 {
    background-color: var(--color-1);
    color: var(--text-0);
}

.hpm-c-2 {
    background-color: var(--color-2);
    color: var(--text-0);
}

.hpm-c-3 {
    background-color: var(--color-3);
    color: var(--text-0);
}

.hpm-hl {
    color: var(--text-hl) !important;
}

.hpm-text-0 {
    color: var(--color-0) !important;
}

.hpm-text-1 {
    color: var(--color-1) !important;
}

.hpm-text-2 {
    color: var(--color-2) !important;
}

.hpm-text-3 {
    color: var(--color-3) !important;
}

.hpm-c-btn-0 {
    background-color: var(--color-0);
    color: var(--text-0);
}

.hpm-c-btn-1 {
    background-color: var(--color-1);
    color: var(--text-0);
}

.hpm-c-btn-2 {
    background-color: var(--color-2);
    color: var(--text-0);
}

.hpm-c-btn-3 {
    background-color: var(--color-3);
    color: var(--text-0);
}
</style>