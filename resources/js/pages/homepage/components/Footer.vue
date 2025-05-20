<template>

    <v-footer name="footer" :color="item.properties.bg_color ?? ''" :class="'text-' + item.properties.color ?? ''"
        :tile="item.properties.is_tile ?? 'true'" dark v-if="item.is_active" :style="footerStyle(item)"
        :app="item.properties.is_app">
        <v-container :fluid="item.properties.is_fluid ?? true"
            class="d-flex justify-space-between align-center h-100 py-0 px-2">
            <v-row no-gutters class="px-0">
                <v-col cols="4" class="d-flex flex-row align-center">
                    <Renderer :item="homepageStore.elementById(item.parts.left.id) ?? null" v-if="item.parts.left" />
                </v-col>
                <v-col cols="4" class="d-flex flex-row align-center">
                    <Renderer :item="homepageStore.elementById(item.parts.center.id) ?? null"
                        v-if="item.parts.center" />
                </v-col>

                <v-col cols="4" class="d-flex flex-row align-center">
                    <Renderer :item="homepageStore.elementById(item.parts.right.id) ?? null" v-if="item.parts.right" />
                </v-col>
            </v-row>
        </v-container>

    </v-footer>




</template>
<script>
import { defineAsyncComponent } from 'vue'

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['item', 'homepageStore'],
    components: { Renderer },


    methods: {
        footerStyle(item) {
            var style = '';
            switch (item.properties.density) {
                case 'prominent': style += ' min-height:128px'; break;
                case 'default': style += ' min-height:64px'; break;
                case 'comfortable': style += ' min-height:56px'; break;
                case 'compact': style += ' min-height:48px'; break;
                default: style += ' min-height:64px'; break;
            }

            return style;
        }
    }

}
</script>