<template>
    <v-app>

        <div>
            <router-view />
            <its-notification />
        </div>



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
        await this.applyThemeFromHomepage();
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
        },
    },

    methods: {
        async ensureStylesheet(id, href) {
            // add a cache-buster so the browser actually refetches when you swap sets
            const finalHref = href + (href.includes('?') ? '&' : '?') + 'v=' + Date.now();

            let link = document.getElementById(id);
            if (!link) {

                link = document.createElement('link');
                link.id = id;
                link.rel = 'stylesheet';
                link.href = href;
                document.head.appendChild(link);
            }

            /*

     const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `/api/css/fontset/${encodeURIComponent(fontset)}.css`;

            // Compare the attribute, not link.href (which is an absolute, normalized URL)
            if (link.getAttribute('href') !== finalHref) {
                link.setAttribute('href', finalHref);
            }
                */

            /*
            // Optionally wait until parsed (handy if you want to run document.fonts after)
            if (!link.sheet || link.sheet.cssRules == null) {
                await new Promise((res) => {
                    link.addEventListener('load', res, { once: true });
                    link.addEventListener('error', res, { once: true });
                });
            }
                */
            return link;
        },

        async applyThemeFromHomepage() {
            const hp = this.homepage || {};
            const colorset = hp?.structure?.colors?.colorset || 'default';
            const fontset = hp?.structure?.fonts?.fontset || 'default';
            console.log(fontset);

            // Load global font faces once (your /public/fonts/fonts.css with all @font-face)
            //  await this.ensureStylesheet('fonts-core', '/fonts/fonts.css');

            // Load set-specific CSS
            await this.ensureStylesheet('colorset-css', `/api/css/colors/${encodeURIComponent(colorset)}.css`);
            await this.ensureStylesheet('fontset-css', `/api/css/fontset/${encodeURIComponent(fontset)}.css`);
            await this.ensureStylesheet('color-bindings', '/css/colors.css'); // <— new

            // (Optional) verify
            // document.fonts.ready.then(()=>console.log('fonts ready'));
        }
    }


};
</script>
