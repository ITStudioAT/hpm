<template>
    <v-container fluid class="ma-0 w-100 pa-2">
        <v-row class="w-100 mb-2" no-gutters>
            <v-col>
                <div class="bg-primary pa-2 text-h5">Basics</div>
            </v-col>
        </v-row>

        <v-form ref="form" @submit.prevent="$emit('save')">
            <!-- Menüleiste oben -->
            <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
                <its-menu-button title="Abbruch" icon="mdi-close" color="warning" @click="abort" />
                <its-menu-button title="Speichern" type="submit" icon="mdi-content-save" color="success"
                    @click="save" />
            </v-row>

            <v-row class="w-100 mb-2" v-if="data">
                <!-- Einstellungen -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title>Einstellungen</v-card-title>
                        <v-card-text>
                            <v-text-field autofocus v-model="data.name" label="Name der Homepage" required />
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Farbenprofil -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            <span>Farbenprofil</span>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-caption text-medium-emphasis mb-1">Ausgewähltes Colorset</div>
                            <div class="mini-row mb-3">
                                <div class="text-body-1 font-weight-medium mr-4">{{ colorsetLabel }}</div>

                                <div class="mini-scale">
                                    <div class="mini-box" :style="{ borderColor: mini.stroke }">
                                        <div class="mini-top">
                                            <span class="mini-dot" :style="{ backgroundColor: mini.first }"></span>
                                            <span class="mini-dot" :style="{ backgroundColor: mini.second }"></span>
                                            <span class="mini-dot" :style="{ backgroundColor: mini.third }"></span>
                                        </div>
                                        <div class="mini-bottom">
                                            <span class="mini-btn"
                                                :style="{ backgroundColor: mini.buttonBg, color: mini.buttonFg, borderColor: mini.stroke }">Aa</span>
                                            <span class="mini-txt" :style="{ color: mini.text }">Text</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <v-select v-model="data.structure.colors.colorset" :items="colorsetOptions"
                                item-title="label" item-value="value" :return-object="false" label="Colorset auswählen"
                                density="comfortable" variant="outlined" hide-details="auto" clearable
                                :disabled="colorsetOptions.length === 0" :loading="colorsetOptions.length === 0"
                                @update:modelValue="onColorsetChanged">
                                <template #selection="{ item }">
                                    <div class="sel-wrap">
                                        <div class="preview">
                                            <ColorsetSwatches :key="item?.raw?.value || currentColorset"
                                                :slug="item?.raw?.value || currentColorset" :gap="8" :rounded="5" />
                                        </div>
                                        <div class="sel-text">
                                            <div class="sel-label">{{ item?.raw?.label ?? prettyLabel(currentColorset)
                                                }}</div>
                                            <div class="sel-sub">{{ item?.raw?.value ?? currentColorset }}</div>
                                        </div>
                                    </div>
                                </template>

                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props" lines="two">
                                        <template #prepend>
                                            <div class="preview">
                                                <ColorsetSwatches :slug="item.raw.value" :gap="8" :rounded="5" />
                                            </div>
                                        </template>
                                        <template #title>{{ item.raw.label }}</template>
                                        <template #subtitle>{{ item.raw.value }}</template>
                                    </v-list-item>
                                </template>
                            </v-select>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Schriftenprofil -->
                <v-col cols="12" md="6" lg="4" xl="3">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            <span>Schriftenprofil</span>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-caption text-medium-emphasis mb-1">Ausgewähltes Schriftbild</div>
                            <div class="mini-row mb-3">
                                <div class="text-body-1 font-weight-medium mr-4">{{ fontsetLabel }}</div>

                                <div class="mini-scale">
                                    <div class="font-mini-box">
                                        <div class="font-lines">
                                            <div class="f-title" :style="fontMini.title">A•a Title</div>
                                            <div class="f-sub" :style="fontMini.subtitle">Subtext</div>
                                            <div class="f-txt" :style="fontMini.content">0123 äöü</div>
                                        </div>
                                        <span class="f-btn" :style="fontMini.button">Button</span>
                                    </div>
                                </div>
                            </div>

                            <v-select v-model="data.structure.fonts.fontset" :items="fontsetOptions" item-title="label"
                                item-value="value" :return-object="false" label="Fontset auswählen"
                                density="comfortable" variant="outlined" hide-details="auto" clearable
                                :disabled="fontsetOptions.length === 0" :loading="fontsetOptions.length === 0"
                                @update:modelValue="onFontsetChanged">
                                <template #selection="{ item }">
                                    <div class="sel-wrap">
                                        <div class="preview preview-fontset">
                                            <div class="font-mini-row">
                                                <span class="f-title-mini" :style="fontMini.title">Aa</span>
                                                <span class="f-btn small" :style="fontMini.button">Button</span>
                                            </div>
                                        </div>
                                        <div class="sel-text">
                                            <div class="sel-label">{{ item?.raw?.label ?? prettyLabel(currentFontset) }}
                                            </div>
                                            <div class="sel-sub">{{ item?.raw?.value ?? currentFontset }}</div>
                                        </div>
                                    </div>
                                </template>

                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props" lines="two">
                                        <template #title>{{ item.raw.label }}</template>
                                        <template #subtitle>{{ item.raw.value }}</template>
                                    </v-list-item>
                                </template>
                            </v-select>
                        </v-card-text>
                    </v-card>
                </v-col>


            </v-row>
            <v-row>
                <v-col cols="12" md="8">
                    <div class="mb-2 font-weight-medium">Desktop</div>
                    <!-- PASS THE COMPONENT -->
                    <ThemeLivePreview :component="ThemeLiveRaw" :componentProps="themeProps" :width="1200"
                        :height="800" />
                </v-col>

                <v-col cols="12" md="4">
                    <div class="mb-2 font-weight-medium">Handy</div>
                    <ThemeLivePreview :component="ThemeLiveRaw" :componentProps="themeProps" :width="390"
                        :height="800" />
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import { h, markRaw } from "vue";
import { mapWritableState } from "pinia";
import { deepMergeDefaults } from "@/helpers/merge";
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
import { useFontsetStore } from "@/stores/admin/FontsetStore";
import { useColorsetStore } from "@/stores/admin/ColorsetStore";
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";
import { useThemePreview } from "../components/useThemePreview";
import ThemeLivePreview from "../components/ThemeLivePreview.vue";
import ThemeLive from "../components/ThemeLive.vue";

/** Farben-Swatches (unverändert) */
const _colorsetCssCache = new Map();
const ColorsetSwatches = {
    name: "ColorsetSwatches",
    props: { slug: { type: String, required: true }, size: { type: Number, default: 18 }, gap: { type: Number, default: 6 }, rounded: { type: Number, default: 4 } },
    data() { return { first: null, second: null, third: null, loading: false }; },
    watch: { slug: { immediate: true, handler() { this.load(); } } },
    methods: {
        parseColorVars(cssText) {
            const pick = (names) => { for (const n of names) { const m = cssText.match(new RegExp(`--${n}\\s*:\\s*([^;]+);`, "i")); if (m) return m[1].trim(); } return null; };
            const first = pick(["firstBackground", "firstBG", "first", "firstColor"]);
            const second = pick(["secondBackground", "secondBG", "second", "secondColor"]);
            const third = pick(["thirdBackground", "thirdBG", "third", "thirdColor"]);
            return { first: first || "#ccc", second: second || "#ddd", third: third || "#eee" };
        },
        async load() {
            if (!this.slug) return;
            if (_colorsetCssCache.has(this.slug)) { Object.assign(this, _colorsetCssCache.get(this.slug)); return; }
            this.loading = true;
            try {
                const url = `/api/css/colors/${encodeURIComponent(this.slug)}.css?v=${Date.now()}`;
                const res = await fetch(url, { headers: { Accept: "text/css,*/*;q=0.1" } });
                const cssText = res.ok ? await res.text() : "";
                const parsed = this.parseColorVars(cssText);
                _colorsetCssCache.set(this.slug, parsed);
                Object.assign(this, parsed);
            } catch {
                const parsed = { first: "#ccc", second: "#ddd", third: "#eee" };
                _colorsetCssCache.set(this.slug, parsed);
                Object.assign(this, parsed);
            } finally { this.loading = false; }
        },
    },
    render() {
        const size = `${this.size}px`, gap = `${this.gap}px`, radius = `${this.rounded}px`;
        const square = (bg) => ({ display: "inline-block", width: size, height: size, borderRadius: radius, border: "1px solid rgba(0,0,0,.08)", boxShadow: "inset 0 0 0 1px rgba(255,255,255,.25)", backgroundColor: bg || "#ccc" });
        return h("div", { style: { display: "inline-flex", alignItems: "center", gap }, "aria-hidden": "true" }, [
            h("span", { style: square(this.first) }),
            h("span", { style: square(this.second) }),
            h("span", { style: square(this.third) }),
        ]);
    },
};

export default {
    props: ["homepage"],
    components: { ItsMenuButton, ColorsetSwatches, ThemeLivePreview },

    async beforeMount() {
        this.adminStore = useAdminStore();
        this.adminStore.initialize(this.$router);
        this.homepageStore = useHomepageStore();
        this.fontsetStore = useFontsetStore();
        this.colorsetStore = useColorsetStore();

        // Composable instanzieren & Core-Fonts laden
        this.previewApi = useThemePreview();
        await this.previewApi.ensureCore({ fontsCoreHref: "/fonts/fonts.css" });

        await Promise.all([
            this.fontsetStore.loadFontsets(),
            this.colorsetStore.loadColorsets(),
            this.fetchChoices(),
        ]);

        const def = { colors: { colorset: "" }, fonts: { fontset: "" } };
        this.data = { ...this.homepage, structure: deepMergeDefaults(this.homepage.structure ?? {}, def) };

        if (!this.data.structure.fonts.fontset) this.data.structure.fonts.fontset = "default";
        if (!this.data.structure.colors.colorset) this.data.structure.colors.colorset = "default";

        // Initiale Previews (mit Cache & Messung)
        await this.updateMiniColors();
        await this.updateFontMini();

        // Debounced Watcher
        this._debouncedColors = this.previewApi.debounce(async (slug) => {
            this.mini = await this.previewApi.getColorsetPreview(slug || "default");
        }, 120);
        this._debouncedFonts = this.previewApi.debounce(async (slug) => {
            this.fontMini = await this.previewApi.getFontsetPreview(slug || "default");
        }, 150);
    },

    data() {
        return {
            adminStore: null,
            homepageStore: null,
            fontsetStore: null,
            colorsetStore: null,
            previewApi: null,
            _debouncedColors: null,
            _debouncedFonts: null,
            ThemeLiveRaw: markRaw(ThemeLive),

            colorChoices: [],
            fontChoices: [],
            mini: { first: "#eee", second: "#ddd", third: "#ccc", buttonBg: "#ccc", buttonFg: "#000", text: "#333", stroke: "rgba(0,0,0,.12)" },
            fontMini: {
                title: { fontFamily: "system-ui, Arial", fontWeight: "700", fontStyle: "normal" },
                subtitle: { fontFamily: "system-ui, Arial", fontWeight: "600", fontStyle: "normal" },
                content: { fontFamily: "system-ui, Arial", fontWeight: "400", fontStyle: "normal" },
                button: { fontFamily: "system-ui, Arial", fontWeight: "700" },
            },
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data"]),
        ...mapWritableState(useFontsetStore, ["fontsets"]),
        ...mapWritableState(useColorsetStore, ["colorsets"]),

        themeProps() {
            return {
                // whatever ThemeLive expects:
                colorset: this.currentColorset,
                fontset: this.currentFontset,
                // anything else your ThemeLive needs…
            };
        },

        currentColorset() { return this.data?.structure?.colors?.colorset || "default"; },
        currentFontset() { return this.data?.structure?.fonts?.fontset || "default"; },

        colorsetLabel() { return this.prettyLabel(this.currentColorset); },
        fontsetLabel() { return this.prettyLabel(this.currentFontset); },

        colorsetOptions() {
            if (this.colorChoices.length) return this.colorChoices;
            const slugs =
                Array.isArray(this.colorsetStore?.options) && this.colorsetStore.options.length ? this.colorsetStore.options :
                    Array.isArray(this.colorsets) ? this.colorsets :
                        this.colorsets && typeof this.colorsets === "object" ? Object.keys(this.colorsets) : [];
            const items = slugs.filter(s => typeof s === "string" && s.trim()).map(s => ({ label: this.prettyLabel(s), value: s }));
            if (!items.some(x => x.value === "default")) items.unshift({ label: "Default", value: "default" });
            return this.uniqueByValue(items);
        },

        fontsetOptions() {
            if (this.fontChoices.length) return this.fontChoices;
            const slugs =
                Array.isArray(this.fontsetStore?.options) && this.fontsetStore.options.length ? this.fontsetStore.options :
                    Array.isArray(this.fontsets) ? this.fontsets :
                        this.fontsets && typeof this.fontsets === "object" ? Object.keys(this.fontsets) : [];
            const items = slugs.filter(s => typeof s === "string" && s.trim()).map(s => ({ label: this.prettyLabel(s), value: s }));
            if (!items.some(x => x.value === "default")) items.unshift({ label: "Default", value: "default" });
            return this.uniqueByValue(items);
        },
    },

    watch: {
        currentColorset(val) { this._debouncedColors?.(val); },
        currentFontset(val) { this._debouncedFonts?.(val); },
    },

    methods: {
        async onColorsetChanged(v) { this.data.structure.colors.colorset = v || "default"; },
        async onFontsetChanged(v) { this.data.structure.fonts.fontset = v || "default"; },

        async updateMiniColors() {
            this.mini = await this.previewApi.getColorsetPreview(this.currentColorset || "default");
        },
        async updateFontMini() {
            this.fontMini = await this.previewApi.getFontsetPreview(this.currentFontset || "default");
        },

        async fetchChoices() {
            try {
                const [cr, fr] = await Promise.all([
                    fetch("/api/colorsets", { headers: { Accept: "application/json" } }),
                    fetch("/api/fontsets", { headers: { Accept: "application/json" } }),
                ]);
                const [cjson, fjson] = await Promise.all([cr.json().catch(() => []), fr.json().catch(() => [])]);

                const cArr = Array.isArray(cjson) ? cjson : [];
                const fArr = Array.isArray(fjson) ? fjson : [];

                this.colorChoices = this.uniqueByValue(
                    cArr.filter(s => typeof s === "string" && s.trim()).map(s => ({ label: this.prettyLabel(s), value: s }))
                );
                if (!this.colorChoices.some(x => x.value === "default"))
                    this.colorChoices.unshift({ label: "Default", value: "default" });

                this.fontChoices = this.uniqueByValue(
                    fArr.filter(s => typeof s === "string" && s.trim()).map(s => ({ label: this.prettyLabel(s), value: s }))
                );
                if (!this.fontChoices.some(x => x.value === "default"))
                    this.fontChoices.unshift({ label: "Default", value: "default" });
            } catch { /* fallback to stores */ }
        },

        uniqueByValue(items) { const seen = new Set(); return items.filter(x => (seen.has(x.value) ? false : (seen.add(x.value), true))); },
        abort() { this.data = null; this.$emit("abort"); },
        async save() { await this.homepageStore.saveHomepage(this.data); this.$emit("save"); },

        prettyLabel(slug) {
            if (!slug || typeof slug !== "string") return "Unbenannt";
            return slug.replace(/[_-]+/g, " ").replace(/\s+/g, " ").trim()
                .replace(/\b([a-zA-Z\u00C0-\u024F])([a-zA-Z\u00C0-\u024F]*)/g, (m, a, b) => a.toUpperCase() + b.toLowerCase());
        },
    },
};
</script>

<style scoped>
.mini-row {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

.mini-scale {
    display: inline-block;
    transform: scale(1.15);
    transform-origin: left center;
    white-space: nowrap;
    min-width: 180px;
}

.sel-wrap {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    max-width: 100%;
}

.sel-text {
    display: grid;
    line-height: 1.1;
}

.sel-label {
    font-weight: 600;
}

.sel-sub {
    font-size: 12px;
    opacity: .7;
}

.preview {
    display: inline-block;
    width: 180px;
}

.mini-box {
    display: inline-block;
    background: #fff;
    border: 2px solid rgba(0, 0, 0, .12);
    border-radius: 12px;
    padding: 10px 10px 8px;
    min-width: 160px;
}

.mini-top {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-bottom: 8px;
}

.mini-dot {
    width: 18px;
    height: 18px;
    border-radius: 999px;
    border: 1px solid rgba(0, 0, 0, .08);
}

.mini-bottom {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mini-btn {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 999px;
    line-height: 1.1;
    font-weight: 700;
    border: 1px solid rgba(0, 0, 0, .12);
    font-size: 12px;
}

.mini-txt {
    font-size: 14px;
}

.font-mini-box {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    border: 2px solid rgba(0, 0, 0, .12);
    border-radius: 12px;
    padding: 10px 12px;
}

.font-lines {
    display: grid;
    gap: 2px;
}

.f-title {
    font-size: 16px;
}

.f-sub {
    font-size: 13px;
    opacity: .8;
}

.f-txt {
    font-size: 12px;
    opacity: .8;
}

.f-btn {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    border: 1px solid rgba(0, 0, 0, .12);
    font-size: 12px;
}

.font-mini-row {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.f-title-mini {
    font-size: 14px;
}

.f-btn.small {
    padding: 2px 8px;
    font-size: 11px;
}
</style>
