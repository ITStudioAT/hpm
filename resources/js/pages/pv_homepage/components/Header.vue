<template>


    <v-app-bar name="app-bar" :class="appClass(hpm)" :density="hpm.density.value ?? 'default'"
        :flat="hpm.is_flat.value ?? 'true'" :tile="hpm.is_tile.value ?? 'true'"
        :scroll-behavior="hpm.scroll_behavior.value ?? ''" v-if="hpm.is_active.value">
        <v-container fluid class="pa-0 h-100 d-flex flex-column">


            <!-- Topline -->
            <component :is="hpm.elements.topline.component.value" :hpm="hpm.elements.topline"
                v-if="hpm.elements.topline.is_active.value == true" />


            <!-- Restliche AppBar-->
            <v-container :fluid="hpm.is_fluid.value ?? true" class=" pa-0 d-flex justify-center h-100">
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
import Topline from "./Topline.vue";
export default {
    props: ['homepageStore', 'hpm'],
    components: { Topline },

    data() {
        return {

        };
    },


    methods: {
        appClass(hpm) {
            var my_class = '';
            if (hpm.bg_color) my_class += ' ' + hpm.bg_color.value;
            if (hpm.color) my_class += ' ' + hpm.color.value;
            return my_class;

        },


        divStyle(item) {
            var style = '';
            // max_width
            if (item.max_width.value && item.max_width.value > 0) style += ' max-width: ' + item.max_width.value + ";";

            return style;
        },


    }


}
</script>