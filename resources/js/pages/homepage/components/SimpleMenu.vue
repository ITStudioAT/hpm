<template>
    <div class="d-flex flex-row align-center menu-wrapper w-100 position-relative" :class="item.properties.justify_text"
        ref="menuWrapper">
        <!-- Menu Items always rendered, only visibility toggled  -->
        <div ref="menuItems" :style="!showAll ? 'visibility:hidden;' : ''" class="position-absolute ">
            <div class="flex-shrink-1 d-flex flex-row align-center ga-2">
                <a v-for="(item, index) in item.properties.menu_items" :key="index" :href="item.href || undefined"
                    :to="item.to || undefined" class="menu-link">
                    {{ item.title }}
                </a>
            </div>
        </div>


        <!-- Hamburger shown when not enough space -->
        <div class="position-absolute menu-hamburger w-100 d-flex flex-row align-center"
            :class="item.properties.justify_icon" :style="showAll ? 'visibility:hidden;' : 'visibility:visible;'">

            <v-menu>
                <template #activator="{ props }">
                    <v-btn icon v-bind="props">
                        <v-icon>mdi-menu</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="(item, index) in item.properties.menu_items" :key="index">
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

        </div>
    </div>
</template>


<script>
import { ref, watch, nextTick } from 'vue'
import { useElementSize } from '../../../composables/useElementSize.js'
import { useHomepageStore } from '../../../stores/HomepageStore'
import { mapWritableState } from 'pinia'

export default {
    props: ['item'],

    mounted() {
        this.homepageStore = useHomepageStore();


        const menuWrapper = this.$refs.menuWrapper;
        const menuItems = this.$refs.menuItems;

        if (menuWrapper) {
            this.resizeObserverWrapper = new ResizeObserver(() => {
                this.menuWrapperWidth = menuWrapper.offsetWidth;
            });
            this.resizeObserverWrapper.observe(menuWrapper);
        }

        if (menuItems) {
            this.resizeObserverItems = new ResizeObserver(() => {
                this.menuItemsWidth = menuItems.offsetWidth;
            });
            this.resizeObserverItems.observe(menuItems);
        }



    },

    beforeUnmount() {
        if (this.resizeObserverWrapper) {
            this.resizeObserverWrapper.disconnect()
        }

        if (this.resizeObserverItems) {
            this.resizeObserverItems.disconnect()
        }
    },

    data() {
        return {
            showAll: true,
            menuWrapperWidth: 0,
            menuItemsWidth: 0,
            resizeObserverWrapper: null,
            resizeObserverItems: null,
        }
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['homepage']),
    },

    watch: {
        menuWrapperWidth() {
            if (this.menuWrapperWidth >= this.menuItemsWidth) this.showAll = true; else this.showAll = false;

        },
        menuItemsWidth() {
            if (this.menuWrapperWidth >= this.menuItemsWidth) this.showAll = true; else this.showAll = false;
        },
    }




}
</script>

<style scoped></style>