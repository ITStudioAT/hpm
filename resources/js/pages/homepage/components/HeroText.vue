<template>
    <div class="h-100  d-flex flex-column align-center justify-center " :style="divStyle(item)">

        <div class="d-flex flex-column align-center justify-center" :style="innerDivStyle(item)">
            <div>{{ item.properties.caption }}</div>
            <div class="text-h1">{{ item.properties.title }}</div>
            <div>{{ item.properties.subtitle }}</div>
        </div>

    </div>

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
            // DENSITY
            switch (item.properties.density) {
                case 'full': style += ' min-height:100vh;'; break;
                case 'prominent': style += ' min-height:80vh;'; break;
                case 'comfortable': style += ' min-height:60vh;'; break;
                case 'default': style += ' min-height:50vh;'; break;
                case 'compact': style += ' min-height:30vh;'; break;
                default: style += ' min-height:50vh;'; break;
            }

            // COLORS
            if (item.properties.bg_color) style += ' background-color: ' + item.properties.bg_color + ";";
            if (item.properties.color) style += ' color: ' + item.properties.color + ";";


            return style;
        },

        innerDivStyle(item) {

            var style = '';

            // ALIGN
            if (item.properties.align == 'above') style += ' transform: translateY(-20vh);'
            else if (item.properties.align == 'below') style += ' transform: translateY(20vh);'

            return style;


        }

    }
}
</script>