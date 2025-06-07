<template>
    <div :style="divStyle(hpm)" :class="divClass(hpm)">
        <v-row no-gutters :style="rowStyle(hpm)" class="h-100">
            <v-col v-for="i in hpm.columns.value" class="h-100">
                <component :is="hpm.elements['col_' + i]?.component?.value" :hpm="hpm.elements['col_' + i]" />
            </v-col>
        </v-row>
    </div>
</template>
<script>
import ShortText from "./ShortText.vue";
export default {
    props: ['hpm', 'homepageStore'],
    components: { ShortText },

    methods: {

        divClass(hpm) {
            var my_class;
            if (hpm.bg_color) my_class += ' ' + hpm.bg_color.value;
            return my_class;


        },

        divStyle(hpm) {
            var my_style = "";
            if (hpm.height) my_style += ' height:' + hpm.height.value + ' !important; ';
            return my_style;
        },

        rowStyle(hpm) {
            var my_style = "";
            if (hpm.height) my_style += ' height:' + hpm.height.value + ' !important; ';
            return my_style;
        }

    }
}
</script>