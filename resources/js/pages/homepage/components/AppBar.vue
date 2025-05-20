<template>

    <v-app-bar name="app-bar" :color="item.properties.bg_color ?? ''" :class="'text-' + item.properties.color ?? ''"
        :density="item.properties.density ?? 'default'" :flat="item.properties.is_flat ?? 'true'"
        :tile="item.properties.is_tile ?? 'true'" :scroll-behavior="item.properties.srcoll_behavior ?? ''" dark
        v-if="item.is_active">
        <v-container :fluid="item.properties.is_fluid ?? true"
            class="d-flex justify-space-between align-center h-100 py-0 px-2" :style="divStyle(item)">
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

    </v-app-bar>

</template>
<script>
import { defineAsyncComponent } from 'vue'

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['item', 'homepageStore'],
    components: { Renderer },


    methods: {
        divStyle(item) {
            var style = '';
            // max_width
            if (item.properties.max_width) style += ' max-width: ' + item.properties.max_width + ";";

            return style;
        },


    }


}
</script>