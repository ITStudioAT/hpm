<template>

    <v-app>

        <v-navigation-drawer v-model="show_navigation_drawer" color="primary" v-if="config && config.is_auth">
            <v-toolbar color="appbar">
                <v-toolbar-title>
                    <div class="text-body-2">Admin</div>
                    <div class="text-caption text-text">{{ config.version }}</div>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon="mdi-menu-close" @click="show_navigation_drawer = false" v-if="show_navigation_drawer" />
            </v-toolbar>
            <v-list>
                <template v-for="(item, i) in config.menu" :key="i">
                    <v-list-item :exact="false" :title="item.title" :prepend-icon="item.icon" v-if="item.to"
                        :to="item.to" />
                    <v-list-item :exact="false" v-if="item.click" :title="item.title" :prepend-icon="item.icon"
                        @click="() => this[item.click]()" />
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar flat color="primary" v-if="config && config.is_auth">
            <template v-slot:prepend>
                <v-btn icon="mdi-menu-open" v-if="!show_navigation_drawer" @click="show_navigation_drawer = true" />
                <v-img :src="'/storage/images/logo.png'" alt="Logo" width="32" class="pl-2"></v-img>

            </template>
        </v-app-bar>



        <v-main class="bg-background" v-if="config && appReady">
            <router-view></router-view>
            <its-notification />
        </v-main>

        <v-footer app>
            <v-row justify="center" no-gutters>
                <v-col cols="12" class="text-center">
                    Fußzeile
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
// ===== Module-scope singletons (persist across re-renders of this component)
const sheetKeyCache = new Map() // id -> key (slug/version) to avoid needless reloads
let applyingTheme = false

import { mapWritableState } from "pinia";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from '@/stores/admin/HomepageStore'

export default {

    components: {},


    async beforeMount() {
        await axios.get('/sanctum/csrf-cookie');
        const id = this.$route.query.id
        this.adminStore = useAdminStore(); this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore()

        await this.adminStore.loadConfig();

        if (this.config.is_auth) await this.homepageStore.loadHomepage(id)
        this.appReady = true

    },





    unmounted() {
    },

    data() {
        return {
            appReady: false,
            adminStore: null,
            homepageStore: null,
        };
    },


    computed: {
        ...mapWritableState(useAdminStore, ['config', 'is_loading', 'show_navigation_drawer', 'load_config']),
        ...mapWritableState(useHomepageStore, ['homepage']),
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

        async logout() {
            await this.adminStore.executeLogout();
            await this.adminStore.loadConfig();
            this.$router.replace('/admin/login');
        },

        hexToRgbTriple(hexOrRgb) {
            if (!hexOrRgb) return '0,0,0'
            const v = hexOrRgb.trim()
            const m = v.match(/rgba?\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i)
            if (m) return `${m[1]},${m[2]},${m[3]}`
            let hex = v.startsWith('#') ? v.slice(1) : v
            if (hex.length === 3) hex = hex.split('').map(c => c + c).join('')
            const r = parseInt(hex.slice(0, 2), 16)
            const g = parseInt(hex.slice(2, 4), 16)
            const b = parseInt(hex.slice(4, 6), 16)
            return `${r},${g},${b}`
        },

        bridgeVuetifyThemeFromHpm() {
            const map = [
                ['background', '--backgroundBackground', '--backgroundText'],
                ['surface', '--mainBackground', '--mainText'],
                ['first', '--firstBackground', '--firstText'],
                ['second', '--secondBackground', '--secondText'],
                ['third', '--thirdBackground', '--thirdText'],
                ['button', '--button', '--buttonText'],
            ]

            const cs = getComputedStyle(document.documentElement)

            const build = (selector) => {
                let css = `${selector}{`
                for (const [token, bgVar, onVar] of map) {
                    const bg = cs.getPropertyValue(bgVar).trim()
                    if (bg) css += `--v-theme-${token}: ${this.hexToRgbTriple(bg)} !important;`
                    const on = onVar ? cs.getPropertyValue(onVar).trim() : ''
                    if (on) css += `--v-theme-on-${token}: ${this.hexToRgbTriple(on)} !important;`
                }
                css += '}'
                return css
            }

            // Write to ALL relevant scopes so Vuetify picks them up
            let css = ''
            css += build(':root')
            css += build('.v-theme--light')
            css += build('.v-theme--dark')

            // (nice-to-have) utility text helpers
            css += `
.text-on-background{ color: rgb(var(--v-theme-on-background)); }
.text-on-surface{ color: rgb(var(--v-theme-on-surface)); }
.text-on-first{ color: rgb(var(--v-theme-on-first)); }
.text-on-second{ color: rgb(var(--v-theme-on-second)); }
.text-on-third{ color: rgb(var(--v-theme-on-third)); }
.text-on-button{ color: rgb(var(--v-theme-on-button)); }
`

            let style = document.getElementById('hpm-vuetify-theme-bridge')
            if (!style) {
                style = document.createElement('style')
                style.id = 'hpm-vuetify-theme-bridge'
                document.head.appendChild(style)
            }
            if (style.textContent !== css) style.textContent = css
        },


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
                    this.ensureStylesheet('fonts-core', '/fonts/fonts.css', 'static'),
                    this.ensureStylesheet('color-bindings', '/css/colors.css', 'static'),
                    this.ensureStylesheet('colorset-css', `/api/css/colors/${encodeURIComponent(colorset)}.css`, `colors:${colorset}`),
                    this.ensureStylesheet('fontset-css', `/api/css/fontset/${encodeURIComponent(fontset)}.css`, `font:${fontset}`),
                ])

                try { await document.fonts.ready } catch { }

                const fam = this.getContentFontFamily()
                this.setBaseFontFamily(fam)
                this.setVuetifyFontVariable(fam)

                // 🔗 NEW: make HPM vars usable via Vuetify's `color` prop
                this.bridgeVuetifyThemeFromHpm()
            } finally {
                applyingTheme = false
            }
        },

    }

}
</script>