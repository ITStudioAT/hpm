<template>
    <div :class="divClass(item)" class="w-100">
        {{ item.properties.text }}

    </div>

</template>
<script>
import { defineAsyncComponent } from 'vue'

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['item', 'homepageStore'],
    components: { Renderer },

    methods: {
        divClass(item) {
            var myClass = '';
            if (item.properties.color) myClass += ' text-' + item.properties.color;
            if (item.properties.size) myClass += ' ' + item.properties.size;
            if (item.properties.justify) myClass += ' ' + item.properties.justify;
            return myClass;
        }
    }
}
</script>