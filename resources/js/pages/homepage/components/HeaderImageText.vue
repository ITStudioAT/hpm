<template>

    <div class="position-relative d-flex flex-row align-center ga-2"
        :style="'height:' + logoHeight(item.properties.logo_size) + ';'">

        <!-- Logo-Box -->
        <div class="d-flex align-center justify-center" style="height: 100%; aspect-ratio: 1;">
            <img :src="'/storage/' + item.properties.logo_url"
                style="max-height: 100%; max-width: 100%; object-fit: contain;" />
        </div>

        <!-- Debug Text -->
        <div>{{ item.properties.text }}</div>
    </div>


</template>
<script>
import { defineAsyncComponent } from 'vue'

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['item', 'homepageStore'],
    components: { Renderer },

    methods: {
        logoHeight(size) {
            switch (size) {
                case 'x-large': return '70px'; break;
                case 'large': return '60px'; break;
                case 'default': return '50px'; break;
                case 'small': return '40px'; break;
                case 'x-small': return '30px'; break;
                default: return '50px'; break;
            }
        }
    }
}
</script>