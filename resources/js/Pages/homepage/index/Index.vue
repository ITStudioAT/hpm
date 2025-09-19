<template>

    <v-layout full-height class="hpm-c-all" v-if="is_ready">
        <v-container class=" d-flex justify-center hpm-c-container pa-0">
            <v-btn @click="colorDefs['--bg-all'] = '#FF0000'">Klick</v-btn>

            <v-card flat tile width="1024" min-height="600">
                <v-toolbar flat tile class="hpm-c-appbar pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Standard</div>
                        <div>Hintergrundfarbe: Farbe-Hintergrund</div>
                        <div>Schriftfarbe: Haupt-Schriftarbe</div>
                    </div>
                </v-toolbar>

                <v-toolbar flat tile class="hpm-c-appbar-0 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 0</div>
                        <div>Hintergrundfarbe: Farbe-0</div>
                        <div>Schriftfarbe: Text-0</div>
                    </div>
                </v-toolbar>
                <v-toolbar flat tile class="hpm-c-appbar-1 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 1</div>
                        <div>Hintergrundfarbe: Farbe-1</div>
                        <div>Schriftfarbe: Text-0</div>
                    </div>
                </v-toolbar>
                <v-toolbar flat tile class="hpm-c-appbar-2 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 2</div>
                        <div>Hintergrundfarbe: Farbe-2</div>
                        <div>Schriftfarbe: Text-0</div>
                    </div>
                </v-toolbar>
                <v-toolbar flat tile class="hpm-c-appbar-3 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 3</div>
                        <div>Hintergrundfarbe: Farbe-3</div>
                        <div>Schriftfarbe: Text-0</div>
                    </div>
                </v-toolbar>
                <v-toolbar flat tile class="hpm-c-appbar-4 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 4</div>
                        <div>Hintergrundfarbe: Farbe-Kopfleiste-4</div>
                        <div>Schriftfarbe: Text-Kopfleiste-4</div>
                    </div>
                </v-toolbar>
                <v-toolbar flat tile class="hpm-c-appbar-5 pa-2 text-caption">
                    <div class="d-block">
                        <div class="text-body-2 font-weight-medium">Kopfleiste Variante 5</div>
                        <div>Hintergrundfarbe: Farbe-Kopfleiste-5</div>
                        <div>Schriftfarbe: Text-Kopfleiste-5</div>
                    </div>
                </v-toolbar>

                <div class="d-flex flex-row align-stretch">
                    <div class="w-50">
                        <!-- VARIANTE STANDARD-->
                        <v-card tile flat class=" h-100 w-100 ma-0 pa-0 hpm-c-0">
                            <v-card-text class="pt-16">

                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Bereich Standard</div>
                                    <div>Hintergrundfarbe: Farbe-0</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-h2 hpm-hl mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Standard</div>
                                    <div>Schriftfarbe: Text-Überschrift</div>
                                </div>

                                <div class="text-h2 hpm-text-1 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 1</div>
                                    <div>Schriftfarbe: Farbe-1</div>
                                </div>

                                <div class="text-h2 hpm-text-2 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 2</div>
                                    <div>Schriftfarbe: Farbe-2</div>
                                </div>
                                <div class="text-h2 hpm-text-3 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 3</div>
                                    <div>Schriftfarbe: Farbe-3</div>
                                </div>

                                <div class="text-body-1 mt-4">
                                    <div>
                                        <div>
                                            Das ist ein Absatztext in der Standardfarbe.
                                        </div>
                                        <div class="mt-2">
                                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatibus sed
                                            minima eius excepturi hic dolorum assumenda eos voluptatum libero nobis.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Standard</div>
                                    <div>Hintergrundfarbe: Farbe-0</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-1   mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 1</div>
                                    <div>Hintergrundfarbe: Farbe-1</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 2</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 3</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 4</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>


                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 5</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </div>

                    <div class="w-50">
                        <!-- VARIANTE 1-->
                        <v-card tile flat class="h-100 ma-0 pa-0 hpm-c-1">
                            <v-card-text class="pt-16">

                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Bereich 1</div>
                                    <div>Hintergrundfarbe: Farbe-1</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-h2 hpm-hl mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Standard</div>
                                    <div>Schriftfarbe: Text-Überschrift</div>
                                </div>

                                <div class="text-h2 hpm-text-0 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 0</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>

                                <div class="text-h2 hpm-text-2 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 2</div>
                                    <div>Schriftfarbe: Farbe-2</div>
                                </div>
                                <div class="text-h2 hpm-text-3 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 3</div>
                                    <div>Schriftfarbe: Farbe-3</div>
                                </div>

                                <div class="text-body-1 mt-4">
                                    <div>
                                        <div>
                                            Das ist ein Absatztext in der Standardfarbe.
                                        </div>
                                        <div class="mt-2">
                                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatibus sed
                                            minima eius excepturi hic dolorum assumenda eos voluptatum libero nobis.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Standard</div>
                                    <div>Hintergrundfarbe: Farbe-0</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-1   mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 1</div>
                                    <div>Hintergrundfarbe: Farbe-1</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 2</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 3</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 4</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>


                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 5</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>
                            </v-card-text>
                        </v-card>

                    </div>
                </div>


                <div class="d-flex flex-row align-stretch">
                    <div class="w-50">
                        <!-- VARIANTE 2-->
                        <v-card tile flat class="h-100 ma-0 pa-0 hpm-c-2">
                            <v-card-text class="pt-16">

                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Bereich 2</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-h2 hpm-hl mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Standard</div>
                                    <div>Schriftfarbe: Text-Überschrift</div>
                                </div>

                                <div class="text-h2 hpm-text-0 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 0</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>

                                <div class="text-h2 hpm-text-1 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 1</div>
                                    <div>Schriftfarbe: Farbe-1</div>
                                </div>
                                <div class="text-h2 hpm-text-3 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 3</div>
                                    <div>Schriftfarbe: Farbe-3</div>
                                </div>

                                <div class="text-body-1 mt-4">
                                    <div>
                                        <div>
                                            Das ist ein Absatztext in der Standardfarbe.
                                        </div>
                                        <div class="mt-2">
                                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatibus sed
                                            minima eius excepturi hic dolorum assumenda eos voluptatum libero nobis.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Standard</div>
                                    <div>Hintergrundfarbe: Farbe-0</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-1   mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 1</div>
                                    <div>Hintergrundfarbe: Farbe-1</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 2</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 3</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 4</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>


                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 5</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </div>

                    <div class="w-50">
                        <!-- VARIANTE 3-->
                        <v-card tile flat class="h-100 ma-0 pa-0 hpm-c-3">
                            <v-card-text class="pt-16">

                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Bereich 3</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-h2 hpm-hl mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Standard</div>
                                    <div>Schriftfarbe: Text-Überschrift</div>
                                </div>

                                <div class="text-h2 hpm-text-0 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 0</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>

                                <div class="text-h2 hpm-text-1 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 1</div>
                                    <div>Schriftfarbe: Farbe-1</div>
                                </div>
                                <div class="text-h2 hpm-text-2 mt-2">Hello World!</div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Überschrift Variante 2</div>
                                    <div>Schriftfarbe: Farbe-2</div>
                                </div>

                                <div class="text-body-1 mt-4">
                                    <div>
                                        <div>
                                            Das ist ein Absatztext in der Standardfarbe.
                                        </div>
                                        <div class="mt-2">
                                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatibus sed
                                            minima eius excepturi hic dolorum assumenda eos voluptatum libero nobis.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Standard</div>
                                    <div>Hintergrundfarbe: Farbe-0</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-1   mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 1</div>
                                    <div>Hintergrundfarbe: Farbe-1</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 2</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>
                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 3</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Text-0</div>
                                </div>

                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-2 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 4</div>
                                    <div>Hintergrundfarbe: Farbe-2</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>


                                <div class="text-right">
                                    <v-btn flat tile class="hpm-c-btn-3 hpm-text-0 mt-4">Bitte Klicken!</v-btn>
                                </div>
                                <div class="d-block">
                                    <div class="text-body-2 font-weight-medium">Button Variante 5</div>
                                    <div>Hintergrundfarbe: Farbe-3</div>
                                    <div>Schriftfarbe: Farbe-0</div>
                                </div>
                            </v-card-text>
                        </v-card>

                    </div>
                </div>



            </v-card>

        </v-container>

    </v-layout>

</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";
export default {

    components: {},

    async beforeMount() {
        const colorset = 'university';
        this.homepageStore = useHomepageStore();
        const response = await axios.get(`/homepage/colorset?colorset=${colorset}`);
        this.colorDefs = response.data.colorDefs;
        this.applyCssVars(this.normalized.value);
        this.is_ready = true;
    },

    mounted() {

    },

    data() {
        return {
            homepageStore: null,
            colorset: null,
            colorDefs: null,
            target: 'root',
            is_ready: false,

        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['config', 'is_loading', 'error']),
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
        }

    }

}
</script>
<style>
:root {
    --bg-all: #3ba3ad;
    --text-all: #FFFFFF;

    --color-0: #FFFFFF;
    --color-1: #DDDDDD;
    --color-2: #f582ae;
    --color-3: #8bd3dd;

    --text-0: #0b333d;
    --text-hl: #000000;

    --appbar-bg-4: #000000;
    --appbar-text-4: #DDDDDD;
    --appbar-bg-5: #0b333d;
    --appbar-text-5: #FFFFFF;
}
</style>
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