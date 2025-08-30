<!-- App.vue -->
<template>
    <v-app>
        <main style="min-height: 100vh;" class="background" v-if="appReady">
            <router-view />
            <VueQueryDevtools v-if="isDev" :initialIsOpen="false" />
            <its-notification />
        </main>

        <!-- Global loading overlay -->
        <div class="d-flex justify-center align-center"
            style="position: fixed; inset: 0; background-color: rgba(255, 255, 255, 0.8); z-index: 9999;"
            v-if="is_loading > 0">
            <v-progress-circular indeterminate size="70" width="7" />
        </div>
    </v-app>
</template>

<script setup>
import { VueQueryDevtools } from '@tanstack/vue-query-devtools'
import ItsNotification from '@/pages/components/ItsNotification.vue'

const isDev = import.meta.env.DEV
</script>

<script>
// ===== Module-scope singletons (persist across re-renders of this component)
const sheetKeyCache = new Map() // id -> key (slug/version) to avoid needless reloads
let applyingTheme = false

import { mapWritableState } from 'pinia'
import { useHomepageStore } from '@/stores/homepage/HomepageStore'

export default {
    // No need to register ItsNotification or Devtools here (script setup handles it)

    async beforeMount() {
        // Usually NOT needed here; call right before POST/PUT/DELETE instead:
        // await axios.get('/sanctum/csrf-cookie')

        const preview = this.$route.query.preview
        this.homepageStore = useHomepageStore()

        this.is_loading++
        await this.homepageStore.loadHomepage(preview)
        this.appReady = true
        this.is_loading--
    },

    data() {
        return {
            appReady: false,
            homepageStore: null,
        }
    },

    computed: {
        ...mapWritableState(useHomepageStore, ['is_loading', 'homepage']),
        colorsetSlug() {
            return this.homepage?.structure?.colors?.colorset || null
        },
        fontsetSlug() {
            return this.homepage?.structure?.fonts?.fontset || null
        },
    },

    watch: {
        // Watch only the slugs that matter (fast + minimal work)
        colorsetSlug: {
            immediate: true,
            handler(val, oldVal) {
                if (val && val !== oldVal) this.applyTheme(val, this.fontsetSlug)
            },
        },
        fontsetSlug: {
            immediate: true,
            handler(val, oldVal) {
                if (val && val !== oldVal) this.applyTheme(this.colorsetSlug, val)
            },
        },
    },

    methods: {
        /**
         * Load/refresh a stylesheet only when its semantic 'key' changes.
         * Keeps a stable URL so the browser cache is effective.
         */
        async ensureStylesheet(id, href, key) {
            if (sheetKeyCache.get(id) === key) return

            let link = document.getElementById(id)
            if (!link) {
                link = document.createElement('link')
                link.id = id
                link.rel = 'stylesheet'
                link.media = 'all'
                document.head.appendChild(link)
            }

            sheetKeyCache.set(id, key)

            await new Promise((res) => {
                link.onload = res
                link.onerror = res // resolve anyway so we never block UI
                link.href = href
            })
        },

        getContentFontFamily() {
            const el = document.createElement('div')
            el.className = 'content'
            el.textContent = 'A'
            el.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none;'
            document.body.appendChild(el)
            const fam =
                getComputedStyle(el).fontFamily ||
                'system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans"'
            el.remove()
            return fam
        },

        setBaseFontFamily(fam) {
            let style = document.getElementById('hpm-basefont')
            const css = `.v-application, body { font-family: ${fam} !important; font-synthesis: none; }`
            if (!style) {
                style = document.createElement('style')
                style.id = 'hpm-basefont'
                document.head.appendChild(style)
            }
            if (style.textContent !== css) style.textContent = css
        },

        setVuetifyFontVariable(fam) {
            let style = document.getElementById('hpm-v-font-var')
            const css = `:root, .v-application { --v-font-family: ${fam}; }`
            if (!style) {
                style = document.createElement('style')
                style.id = 'hpm-v-font-var'
                document.head.appendChild(style)
            }
            if (style.textContent !== css) style.textContent = css
        },

        /**
         * Apply theme by loading static + slugged styles in PARALLEL.
         * No cache-busting; only re-run when slugs change.
         */
        async applyTheme(colorset, fontset) {
            if (!colorset || !fontset || applyingTheme) return
            applyingTheme = true

            try {
                await Promise.all([
                    // Static, load once
                    this.ensureStylesheet('fonts-core', '/fonts/fonts.css', 'static'),
                    this.ensureStylesheet('color-bindings', '/css/colors.css', 'static'),

                    // Slugged: only change when slug changes
                    this.ensureStylesheet(
                        'colorset-css',
                        `/api/css/colors/${encodeURIComponent(colorset)}.css`,
                        `colors:${colorset}`
                    ),
                    this.ensureStylesheet(
                        'fontset-css',
                        `/api/css/fontset/${encodeURIComponent(fontset)}.css`,
                        `font:${fontset}`
                    ),
                ])

                // Wait for fonts to settle (non-blocking if already ready)
                try {
                    await document.fonts.ready
                } catch { }

                const fam = this.getContentFontFamily()
                this.setBaseFontFamily(fam)
                this.setVuetifyFontVariable(fam)
            } finally {
                applyingTheme = false
            }
        },
    },
}
</script>

<!-- Optional: put app-wide tweaks here -->
<style>
/* Ensure your @font-face rules use: font-display: swap; in the served CSS */
</style>
