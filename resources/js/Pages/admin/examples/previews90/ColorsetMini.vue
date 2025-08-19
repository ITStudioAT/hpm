<template>
    <div class="mini">
        <div v-if="ready" class="tile" :style="{ borderColor: val('--strokeColor', '#d0d0d0') }">
            <div class="row">
                <!-- Vier einfache Kästchen: Background / Main / Button / Accent -->
                <div class="sq" :style="{ background: val('--backgroundBackground', '#ffffff') }"
                    title="backgroundBackground"></div>
                <div class="sq" :style="{ background: val('--mainBackground', '#f7f7f7') }" title="mainBackground">
                </div>
                <div class="sq" :style="{ background: val('--buttonBackground', '#111111') }" title="buttonBackground">
                </div>
                <div class="sq"
                    :style="{ background: val('--firstBackground', val('--highlightBackground', '#dddddd')) }"
                    title="first/highlight"></div>
            </div>
            <div class="bar">
                <span class="btn" :style="{
                    background: val('--buttonBackground', '#111'),
                    color: val('--buttonText', '#fff'),
                    borderColor: val('--strokeColor', 'transparent')
                }">Aa</span>
                <span class="txt" :style="{ color: val('--contentText', '#222') }">Text</span>
            </div>
        </div>

        <div v-else class="mini-skeleton" />
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
    /** z.B. "education" */
    value: { type: String, default: 'default' }
})

/** Stabiler, HMR-fester Modul-Cache */
const COLORSET_VAR_CACHE =
    typeof window !== 'undefined'
        ? (window.__COLORSET_VAR_CACHE__ ??= new Map())
        : new Map()

const ready = ref(false)
const vars = ref({})

onMounted(() => load(props.value || 'default'))
watch(() => props.value, slug => load(slug || 'default'))

async function load(slug) {
    ready.value = false

    // Cache-Hit?
    if (COLORSET_VAR_CACHE.has(slug)) {
        vars.value = COLORSET_VAR_CACHE.get(slug)
        ready.value = true
        return
    }

    try {
        const res = await fetch(`/api/css/colors/${encodeURIComponent(slug)}.css`, {
            headers: { Accept: 'text/css' },
            credentials: 'omit',    // vermeidet SameSite/XSRF-Warnungen
            cache: 'no-store'
        })
        const css = await res.text()
        const parsed = parseCssVars(css)
        COLORSET_VAR_CACHE.set(slug, parsed)
        vars.value = parsed
    } catch (e) {
        console.warn('ColorsetMini: fetch failed for', slug, e)
        vars.value = {}
    } finally {
        ready.value = true
    }
}

function parseCssVars(cssText) {
    const map = {}
    const re = /--([a-zA-Z0-9_-]+)\s*:\s*([^;]+);/g
    let m
    while ((m = re.exec(cssText))) {
        const k = `--${m[1]}`
        if (!(k in map)) map[k] = m[2].trim()
    }
    return map
}

function val(name, fallback) {
    return vars.value?.[name] ?? fallback
}
</script>

<style scoped>
.mini {
    display: inline-block;
    /* Breite steuerst du extern via :deep(.mini) */
    height: 62px;
}

.tile {
    box-sizing: border-box;
    width: 100%;
    height: 100%;
    border: 1px solid;
    border-radius: 12px;
    padding: 6px 8px;
    background: #fff;
    display: grid;
    grid-template-rows: auto 1fr;
    gap: 6px;
}

.row {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 1fr;
    gap: 6px;
}

.sq {
    height: 16px;
    border-radius: 6px;
    border: 1px solid rgba(0, 0, 0, .06);
}

.bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.btn {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 8px;
    font-size: 12px;
    line-height: 1;
    border: 1px solid transparent;
}

.txt {
    font-size: 12px;
    opacity: .85;
}

.mini-skeleton {
    width: 100%;
    height: 100%;
    border-radius: 12px;
    background: repeating-linear-gradient(45deg, #eee, #eee 8px, #f7f7f7 8px, #f7f7f7 16px);
    border: 1px solid rgba(0, 0, 0, .06);
}
</style>
