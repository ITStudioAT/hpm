<template>


    <v-app-bar name="app-bar" :color="hpm.bg_color.value ?? ''" :class="'text-' + hpm.color.value ?? ''"
        :density="hpm.density.value ?? 'default'" :flat="hpm.is_flat.value ?? 'true'"
        :tile="hpm.is_tile.value ?? 'true'" :scroll-behavior="hpm.scroll_behavior.value ?? ''"
        v-if="hpm.is_active.value">
        <v-container fluid class="pa-0 h-100 d-flex flex-column">


            <!-- Topline -->
            <v-row no-gutters class="flex-shrink-1" v-if="hpm.elements.topline.is_active.value == true">Hello
                World!</v-row>


            <!-- Restliche AppBar-->
            <v-container :fluid="hpm.is_fluid.value ?? true" class=" pa-0 d-flex justify-center flex-grow-1 h-100">
                <v-row no-gutters :style="divStyle(hpm)"
                    class="d-flex justify-space-between align-center h-100 py-0 px-2 bg-green">
                    <v-col cols="4" class="d-flex flex-row align-center">
                        a
                        <!--
                    <Renderer :item="homepageStore.elementById(item.parts.left.id) ?? null" v-if="item.parts.left" />
                    -->
                    </v-col>
                    <v-col cols="4" class="d-flex flex-row align-center">
                        b
                        <!--
                    <Renderer :item="homepageStore.elementById(item.parts.center.id) ?? null"
                        v-if="item.parts.center" />
                        -->

                    </v-col>

                    <v-col cols="4" class="d-flex flex-row align-center">
                        c
                        <!--
                    <Renderer :item="homepageStore.elementById(item.parts.right.id) ?? null" v-if="item.parts.right" />
                    -->
                    </v-col>
                </v-row>

            </v-container>
        </v-container>

    </v-app-bar>

</template>
<script>
import { defineAsyncComponent } from 'vue'

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['homepageStore'],
    components: { Renderer },

    data() {
        return {
            hpm: {
                name: 'Header',
                type: 'header',
                component: 'header',
                is_active: {
                    value: true, name: 'Aktiv', options_type: { type: 'boolean' },
                    options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                },
                is_fluid: {
                    value: false, name: 'Fluid', options_type: { type: 'boolean' },
                    options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                },
                max_width: {
                    value: 1280, name: 'Max. Breite', options_type: { type: 'integer', range: [0, 2048] }
                },
                bg_color: {
                    value: 'white', name: 'Hintergrundfarbe', options_type: { type: 'color' }
                },
                color: {
                    value: 'black', name: 'Textfarbe', options_type: { type: 'color' }
                },
                density: {
                    value: 'prominent', name: 'Density', options_type: { type: 'multi' },
                    options: [{ option: 'prominent', value: 'prominent' }, { option: 'default', value: 'default' }, { option: 'comfortable', value: 'comfortable' }, { option: 'compact', value: 'compact' }]
                },
                is_flat: {
                    value: false, name: 'Flach', options_type: { type: 'boolean' },
                    options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                },
                is_tile: {
                    value: false, name: 'Ohne Schatten', options_type: { type: 'boolean' },
                    options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                },
                scroll_behavior: {
                    value: 'hide', name: 'Scroll-Verhalten', options_type: { type: 'multi' },
                    options: [{ option: 'hide', value: 'hide' }]
                },

                elements: {
                    topline: {
                        is_active: {
                            value: true, name: 'Aktiv', options_type: { type: 'boolean' },
                            options: [{ option: 'ein', value: true }, { option: 'aus', value: false }]
                        },
                        type: {
                            value: 'short_text', name: 'Typ', options_type: { type: 'multi' },
                            options: [{ option: 'Kurzer Text', value: 'short_text' }]
                        },
                        short_text: {
                            elements: {
                                text: 'Das ist doch ein Wunder', name: 'Text', options_type: { type: 'text', max: 255 }
                            }
                        }

                    },
                    appbar_left: {

                    },
                    appbar_center: {

                    },
                    appbar_right: {

                    },

                }
            },
        };
    },


    methods: {
        divStyle(item) {
            var style = '';
            // max_width
            if (item.max_width.value && item.max_width.value > 0) style += ' max-width: ' + item.max_width.value + "px;";

            return style;
        },


    }


}
</script>