<!-- MiniColorFontPicker.vue -->
<template>
    <v-container fluid class="pa-0">
        <!-- COLORS -->
        <div class="mb-2 d-flex align-center">
            <div class="text-subtitle-1 font-weight-medium">Farbschema</div>
            <v-spacer />
            <v-btn size="small" variant="text" :loading="loadingColors" @click="loadColors">Neu laden</v-btn>
        </div>

        <v-item-group :model-value="localColorset" @update:model-value="val => selectColorset(String(val))" mandatory
            class="mb-6">
            <v-row dense>
                <v-col v-for="name in colorsets" :key="'color-' + name" cols="6" sm="4" md="3" lg="2" xl="2">
                    <v-item :value="name" v-slot="{ isSelected, toggle }">
                        <v-card :elevation="isSelected ? 6 : 2"
                            :class="['mini-card', isSelected ? 'mini-card--active' : '']" role="button"
                            @click="() => { toggle(); selectColorset(name) }">
                            <div class="mini-frame-wrap" v-intersect="(is, obs, e) => onIntersect('color', name, is)">
                                <iframe v-if="isVisible.color[name]" class="mini-frame" :title="`Colorset ${name}`"
                                    sandbox=""
                                    :srcdoc="miniDoc({ colorset: name, fontset: localFontset, mode: 'color' })" />
                                <div v-else class="mini-skeleton"></div>
                            </div>
                            <div class="mini-label">
                                <v-icon size="16" class="mr-1"
                                    :icon="isSelected ? 'mdi-check-circle' : 'mdi-palette'"></v-icon>
                                <span class="truncate">{{ name }}</span>
                            </div>
                        </v-card>
                    </v-item>
                </v-col>
            </v-row>
        </v-item-group>

        <!-- FONTS -->
        <div class="mb-2 d-flex align-center">
            <div class="text-subtitle-1 font-weight-medium">Schriftprofil</div>
            <v-spacer />
            <v-btn size="small" variant="text" :loading="loadingFonts" @click="loadFonts">Neu laden</v-btn>
        </div>

        <v-item-group :model-value="localFontset" @update:model-value="val => selectFontset(String(val))" mandatory>
            <v-row dense>
                <v-col v-for="name in fontsets" :key="'font-' + name" cols="6" sm="4" md="3" lg="2" xl="2">
                    <v-item :value="name" v-slot="{ isSelected, toggle }">
                        <v-card :elevation="isSelected ? 6 : 2"
                            :class="['mini-card', isSelected ? 'mini-card--active' : '']" role="button"
                            @click="() => { toggle(); selectFontset(name) }">
                            <div class="mini-frame-wrap" v-intersect="(is, obs, e) => onIntersect('font', name, is)">
                                <iframe v-if="isVisible.font[name]" class="mini-frame" :title="`Fontset ${name}`"
                                    sandbox=""
                                    :srcdoc="miniDoc({ colorset: localColorset, fontset: name, mode: 'font' })" />
                                <div v-else class="mini-skeleton"></div>
                            </div>
                            <div class="mini-label">
                                <v-icon size="16" class="mr-1"
                                    :icon="isSelected ? 'mdi-check-circle' : 'mdi-format-font'"></v-icon>
                                <span class="truncate">{{ name }}</span>
                            </div>
                        </v-card>
                    </v-item>
                </v-col>
            </v-row>
        </v-item-group>
    </v-container>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    colorset: { type: String, default: 'default' },
    fontset: { type: String, default: 'default' }
})
const emit = defineEmits(['update:colorset', 'update:fontset'])

const colorsets = ref([])
const fontsets = ref([])

const loadingColors = ref(false)
const loadingFonts = ref(false)

const localColorset = ref(props.colorset || 'default')
const localFontset = ref(props.fontset || 'default')

watch(() => props.colorset, v => { if (v && v !== localColorset.value) localColorset.value = v })
watch(() => props.fontset, v => { if (v && v !== localFontset.value) localFontset.value = v })

function selectColorset(name) {
    localColorset.value = name
    emit('update:colorset', name)
}
function selectFontset(name) {
    localFontset.value = name
    emit('update:fontset', name)
}

async function loadColors() {
    loadingColors.value = true
    try {
        const { data } = await axios.get('/api/colorsets')
        // ensure string array, unique, sorted by name
        colorsets.value = Array.from(new Set((data || []).map(String))).sort((a, b) => a.localeCompare(b))
    } finally {
        loadingColors.value = false
    }
}
async function loadFonts() {
    loadingFonts.value = true
    try {
        const { data } = await axios.get('/api/fontsets')
        fontsets.value = Array.from(new Set((data || []).map(String))).sort((a, b) => a.localeCompare(b))
    } finally {
        loadingFonts.value = false
    }
}

onMounted(() => {
    loadColors()
    loadFonts()
})

/**
 * Lazy show iframes only once visible
 */
const isVisible = ref({ color: {}, font: {} })
function onIntersect(kind, name, is) {
    if (is) isVisible.value[kind][name] = true
}

/**
 * Build a tiny HTML document that uses your real CSS endpoints
 * Colors: /api/css/colors/:name.css
 * Fonts:  /api/css/fontset/:name.css (+ /fonts/fonts.css)
 */
function miniDoc({ colorset, fontset, mode }) {
    const v = Date.now() // cache-bust while you iterate
    // Minimal demo block uses your CSS variables & font tokens
    return `
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/api/css/colors/${encodeURIComponent(colorset)}.css?v=${v}">
  <link rel="stylesheet" href="/fonts/fonts.css?v=${v}">
  <link rel="stylesheet" href="/api/css/fontset/${encodeURIComponent(fontset)}.css?v=${v}">
  <style>
    html,body { margin:0; padding:0; }
    .wrap {
      box-sizing:border-box;
      width:100%; height:100%;
      background: var(--mainBackground, #fff);
      color: var(--contentText, #222);
      display:flex; align-items:center; justify-content:center;
      padding:8px;
      font: var(--contentFont, 400 13px/1.35 sans-serif);
    }
    .card {
      width:100%; height:100%;
      border:1px solid var(--strokeColor, #ddd);
      border-radius:10px;
      overflow:hidden;
      display:grid; grid-template-rows:auto 1fr auto;
      background: var(--backgroundBackground,#fff);
    }
    .hero {
      padding:6px 8px;
      font: var(--heroTitleFont, 600 14px/1.1 sans-serif);
      color: var(--heroTitleText,#111);
      white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .lead {
      padding:0 8px 6px 8px;
      font: var(--heroLeadFont, 400 11px/1.2 sans-serif);
      color: var(--heroLeadText,#555);
      white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .btn {
      margin:6px 8px 8px 8px;
      display:inline-block; text-align:center;
      padding:6px 8px;
      background: var(--buttonBackground,#000);
      color: var(--buttonText,#fff);
      border-radius:8px;
      font: var(--buttonFont, inherit);
      border: 1px solid var(--strokeColor, transparent);
    }
    /* Make the font preview pop a bit when mode === 'font' */
    ${mode === 'font' ? `
      .hero { font-size:16px; }
      .lead { font-size:12px; }
    ` : ''}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="hero">Aa The quick brown</div>
      <div class="lead">Subtext & symbols 012345</div>
      <div style="display:flex; justify-content:flex-end;"><span class="btn">Button</span></div>
    </div>
  </div>
</body>
</html>
  `.trim()
}
</script>

<style scoped>
.mini-card {
    border: 2px solid rgba(0, 0, 0, 0.06);
    border-radius: 14px;
    transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
    cursor: pointer;
}

.mini-card:hover {
    transform: translateY(-2px);
}

.mini-card--active {
    border-color: rgba(0, 0, 0, 0.25);
}

.mini-frame-wrap {
    width: 100%;
    aspect-ratio: 16/10;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    background: #f6f6f8;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    overflow: hidden;
}

.mini-frame {
    width: 100%;
    height: 100%;
    border: 0;
}

.mini-skeleton {
    width: 100%;
    height: 100%;
    background: repeating-linear-gradient(45deg, #eee, #eee 8px, #f7f7f7 8px, #f7f7f7 16px);
}

.mini-label {
    display: flex;
    align-items: center;
    padding: 8px 10px;
    font-size: 0.82rem;
}

.truncate {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
