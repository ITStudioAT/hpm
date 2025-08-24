<template>
    <v-app>

        <main style="min-height: 100vh;" class="background" v-if="appReady">
            <router-view />
            <VueQueryDevtools v-if="isDev" :initialIsOpen="false" />
            <its-notification />
        </main>


        <!-- Es wird aktuell etwas geladen-->
        <div class="d-flex justify-center align-center"
            style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); z-index: 9999;"
            v-if="is_loading > 0">
            <v-progress-circular indeterminate size="70" width="7" />
        </div>
    </v-app>
</template>

<script setup>
import { VueQueryDevtools } from '@tanstack/vue-query-devtools'
import ItsNotification from "@/pages/components/ItsNotification.vue";
const isDev = import.meta.env.DEV


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
        this.is_loading++;
        await this.homepageStore.loadHomepage(preview);
        //await this.applyThemeFromHomepage();
        this.appReady = true;
        this.is_loading--;
    },

    data() {
        return {
            appReady: false,
            homepageStore: null,
            lastFontset: null,
            lastColorset: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["is_loading", "homepage"]),
    },

    // App.vue (script section) — FINAL TIDY
    watch: {
        homepage: {
            deep: true,
            async handler() {
                await this.applyThemeFromHomepage();
            }
        }
    },

    methods: {
        async ensureStylesheet(id, href) {
            const finalHref = href + (href.includes('?') ? '&' : '?') + 'v=' + Date.now();
            let link = document.getElementById(id);
            if (!link) {
                link = document.createElement('link');
                link.id = id; link.rel = 'stylesheet';
                document.head.appendChild(link);
            }
            if (link.getAttribute('href') !== finalHref) {
                await new Promise(res => {
                    link.addEventListener('load', res, { once: true });
                    link.addEventListener('error', res, { once: true });
                    link.setAttribute('href', finalHref);
                });
            }
            return link;
        },

        getContentFontFamily() {
            const el = document.createElement('div');
            el.className = 'content'; el.textContent = 'A';
            el.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none;';
            document.body.appendChild(el);
            const fam = getComputedStyle(el).fontFamily || '';
            el.remove();
            return fam || 'system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans"';
        },

        setBaseFontFamily(fam) {
            let style = document.getElementById('hpm-basefont');
            const css = `.v-application, body { font-family: ${fam} !important; font-synthesis: none; }`;
            if (!style) { style = document.createElement('style'); style.id = 'hpm-basefont'; document.head.appendChild(style); }
            if (style.textContent !== css) style.textContent = css;
        },

        setVuetifyFontVariable(fam) {
            let style = document.getElementById('hpm-v-font-var');
            const css = `:root, .v-application { --v-font-family: ${fam}; }`;
            if (!style) { style = document.createElement('style'); style.id = 'hpm-v-font-var'; document.head.appendChild(style); }
            if (style.textContent !== css) style.textContent = css;
        },

        async applyThemeFromHomepage() {
            const colorset = this.homepage?.structure?.colors?.colorset;
            const fontset = this.homepage?.structure?.fonts?.fontset;
            if (!colorset || !fontset) return; // guard

            await this.ensureStylesheet('fonts-core', '/fonts/fonts.css');
            await this.ensureStylesheet('colorset-css', `/api/css/colors/${encodeURIComponent(colorset)}.css`);
            await this.ensureStylesheet('fontset-css', `/api/css/fontset/${encodeURIComponent(fontset)}.css`);
            await this.ensureStylesheet('color-bindings', '/css/colors.css');

            await new Promise(r => requestAnimationFrame(r));
            const fam = this.getContentFontFamily();
            this.setBaseFontFamily(fam);
            this.setVuetifyFontVariable(fam);
        }
    }
};
</script>
