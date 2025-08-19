<template>
    <v-app>
        <v-app-bar flat color="primary">
            <template #prepend>
                <v-img :src="'/storage/images/logo.png'" alt="Logo" width="32" class="pl-2" />
            </template>
        </v-app-bar>

        <v-main class="bg-background">
            <router-view />
            <its-notification />
        </v-main>

        <v-footer app>
            <v-row justify="center" no-gutters>
                <v-col cols="12" class="text-center">
                    {{ homepage }}
                </v-col>
            </v-row>
        </v-footer>

        <!-- Es wird aktuell etwas geladen-->
        <div class="d-flex justify-center align-center"
            style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); z-index: 9999;"
            v-if="is_loading > 0">
            <v-progress-circular indeterminate size="70" width="7" />
        </div>
    </v-app>
</template>

<script setup>
import ItsNotification from "@/pages/components/ItsNotification.vue";
</script>

<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {
    components: {},

    async beforeMount() {
        await axios.get("/sanctum/csrf-cookie");

        const preview = this.$route.query.preview;
        this.homepageStore = useHomepageStore();
        await this.homepageStore.loadHomepage(preview);
    },

    data() {
        return {
            homepageStore: null,
            lastFontset: null,
            lastColorset: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["is_loading", "homepage"]),
    },

    watch: {
        // Apply theme as soon as homepage arrives or changes
        homepage: {
            deep: true,
            immediate: true,
            handler() {
                this.applyThemeFromHomepage();
            },
        },
    },

    methods: {




        async applyThemeFromHomepage() {
            const hp = this.homepage || {};
            const colorset = hp?.structure?.colors?.colorset || "default";
            const fontset = hp?.structure?.fonts?.fontset || "default";

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `/api/css/fontset/${encodeURIComponent(fontset)}.css`;
            console.log(`/api/css/fontset/${encodeURIComponent(fontset)}.css`);
            console.log(link.href);
            document.head.appendChild(link);
        },
    }

};
</script>
