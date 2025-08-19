<template>
  <div class="mini">
    <div v-if="ready" class="tile">
      <div class="hero" :style="heroStyle">Aa Quick brown</div>
      <div class="lead" :style="leadStyle">0123 äöü — Subtext</div>
      <div class="bar">
        <span class="btn" :style="buttonStyle">Button</span>
      </div>
    </div>
    <div v-else class="mini-skeleton" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  value: { type: String, default: 'default' } // fontset slug
})

/** HMR-fester Modulcache */
const FONTSET_VAR_CACHE =
  typeof window !== 'undefined'
    ? (window.__FONTSET_VAR_CACHE__ ??= new Map())
    : new Map()

const ready = ref(false)
const vars = ref({})

onMounted(() => {
  // einmalig globale Schrift-Dateien sicher laden (ohne Cookies)
  if (typeof document !== 'undefined' &&
    !document.querySelector('link[data-fonts-global="1"]')) {
    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = '/fonts/fonts.css'
    link.setAttribute('data-fonts-global', '1')
    document.head.appendChild(link)
  }
  load(props.value || 'default')
})

watch(() => props.value, slug => load(slug || 'default'))

async function load(slug) {
  ready.value = false

  if (FONTSET_VAR_CACHE.has(slug)) {
    vars.value = FONTSET_VAR_CACHE.get(slug)
    ready.value = true
    return
  }

  try {
    const res = await fetch(`/api/css/fontset/${encodeURIComponent(slug)}.css`, {
      headers: { Accept: 'text/css' },
      credentials: 'omit',
      cache: 'no-store'
    })
    const css = await res.text()
    const parsed = parseCssVars(css)
    FONTSET_VAR_CACHE.set(slug, parsed)
    vars.value = parsed
  } catch (e) {
    console.warn('FontsetMini: fetch failed for', slug, e)
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

function v(name, fallback) {
  return vars.value?.[name] ?? fallback
}

// Wir nutzen die im Fontset definierten Shorthands (falls vorhanden):
//  --heroTitleFont, --heroLeadFont, --contentFont, --buttonFont
const heroStyle = computed(() => ({
  font: v('--heroTitleFont', '600 16px/1.15 system-ui, sans-serif'),
  fontSize: '16px' // zur Sicherheit klein halten
}))
const leadStyle = computed(() => ({
  font: v('--heroLeadFont', v('--contentFont', '400 12px/1.3 system-ui, sans-serif')),
  fontSize: '12px'
}))
const buttonStyle = computed(() => ({
  font: v('--buttonFont', v('--contentFont', '600 12px/1.1 system-ui, sans-serif')),
  fontSize: '12px',
  background: '#111',
  color: '#fff',
  border: '1px solid #ddd',
  borderRadius: '8px',
  padding: '4px 8px',
  display: 'inline-block'
}))
</script>

<style scoped>
.mini {
  display: inline-block;
  height: 62px;
}

.tile {
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  border: 1px solid rgba(0, 0, 0, .12);
  border-radius: 12px;
  padding: 6px 8px;
  background: #fff;
  display: grid;
  grid-template-rows: auto auto 1fr;
  gap: 4px;
}

.hero {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.lead {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  opacity: .9;
}

.bar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.mini-skeleton {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  background: repeating-linear-gradient(45deg, #eee, #eee 8px, #f7f7f7 8px, #f7f7f7 16px);
  border: 1px solid rgba(0, 0, 0, .06);
}
</style>
