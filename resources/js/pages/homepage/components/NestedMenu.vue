<template>
    <v-list-item link>
        <v-list-item-title>{{ item.title }}</v-list-item-title>

        <template v-if="hasChildren" v-slot:append>
            <v-icon icon="mdi-menu-right" size="x-small" />
        </template>

        <v-menu v-if="hasChildren" activator="parent" submenu>
            <v-list>
                <NestedMenu v-for="(child, index) in item.children" :key="index" :item="child" />
            </v-list>
        </v-menu>
    </v-list-item>
</template>

<script>
import { computed, defineAsyncComponent } from 'vue'

export default {
    name: 'NestedMenu',

    components: {
        NestedMenu: defineAsyncComponent(() => import('./NestedMenu.vue'))
    },

    props: {
        item: {
            type: Object,
            required: true
        }
    },

    setup(props) {
        const hasChildren = computed(() => {
            return Array.isArray(props.item.children) && props.item.children.length > 0
        })

        return { hasChildren }
    }
}
</script>
