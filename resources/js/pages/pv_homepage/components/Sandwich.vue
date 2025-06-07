<template>
    <div class="d-flex flex-row align-center w-100" :class="item.properties.justify ?? 'justify-end'">
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-btn :icon="item.properties.icon" variant="text" v-bind="props"></v-btn>
            </template>
            <v-list>
                <NestedMenu v-for="(item, index) in menuItems" :key="index" :item="item" />
            </v-list>
        </v-menu>
    </div>

</template>
<script>
import { defineAsyncComponent } from 'vue'
import NestedMenu from './NestedMenu.vue';

const Renderer = defineAsyncComponent(() => import('./Renderer.vue'))
export default {
    props: ['item', 'homepageStore'],
    components: { Renderer, NestedMenu },

    data() {
        return {
            menuItems: [
                {
                    title: 'Item 1',
                    children: [
                        {
                            title: 'Item 1.1',
                            children: [
                                { title: 'Item 1.1.1' },
                                { title: 'Item 1.1.2' }
                            ]
                        },
                        {
                            title: 'Item 1.2'
                        }
                    ]
                },
                {
                    title: 'Item 2',
                    children: [
                        { title: 'Item 2.1' }
                    ]
                },
                {
                    title: 'Item 3'
                }
            ]

        };
    },

    methods: {

    }
}
</script>