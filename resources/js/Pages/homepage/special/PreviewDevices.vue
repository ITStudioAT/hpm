<template>
    <v-container class="preview-devices" fluid>
        <v-row class="toolbar" align="center" justify="space-between">
            <v-col cols="12" md="6">
                <v-row align="center" dense>
                    <v-col cols="12" md="6">
                        <v-select v-model="selectedPreset" :items="presetOptions" item-title="label" item-value="value"
                            label="Device preset" density="compact" />
                    </v-col>
                    <v-col cols="auto">
                        <v-btn variant="outlined" @click="rotate" size="small">
                            <v-icon start>mdi-rotate-90</v-icon>
                            Rotate
                        </v-btn>
                    </v-col>
                </v-row>
            </v-col>

            <v-col cols="12" md="6">
                <v-row align="center" dense>
                    <v-col cols="6">
                        <v-text-field v-model="localFontset" label="Fontset" density="compact" @keyup.enter="reload" />
                    </v-col>
                    <v-col cols="6">
                        <v-text-field v-model="localColorset" label="Colorset" density="compact"
                            @keyup.enter="reload" />
                    </v-col>
                    <v-col cols="auto">
                        <v-btn color="primary" @click="reload" size="small">Reload</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <v-row v-if="selectedPreset === 'custom'" class="custom" dense>
            <v-col cols="12" md="4">
                <v-text-field v-model.number="width" type="number" label="Width (px)" density="compact" />
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field v-model.number="height" type="number" label="Height (px)" density="compact" />
            </v-col>
            <v-col cols="12" md="4">
                <v-slider v-model.number="zoom" min="50" max="300" step="10" label="Zoom (%)" thumb-label />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <div ref="frameWrap" class="frame-wrap d-flex justify-center">
                    <div class="frame-outer" :style="outerStyle">
                        <iframe ref="iframeRef" class="frame" :src="iframeUrl" :style="frameStyle"
                            frameborder="0"></iframe>
                    </div>
                </div>
            </v-col>
        </v-row>

        <v-row class="url-bar" align="center" dense>
            <v-col>
                <span class="mono">{{ computedUrl }}</span>
            </v-col>
            <v-col cols="auto">
                <v-btn variant="outlined" @click="copyUrl" size="small">Copy URL</v-btn>
                <v-chip v-if="copied" color="success" size="small">Copied!</v-chip>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    fontset: { type: String, default: 'default' },
    colorset: { type: String, default: 'autumn' },
    path: { type: String, default: '/special/preview_colors_and_fonts' },
    initialPreset: { type: String, default: 'desktop' },
})

const presets = [
    { id: 'desktop', name: 'Desktop', width: 1440, height: 900 },
    { id: 'laptop', name: 'Laptop', width: 1280, height: 800 },
    { id: 'tablet', name: 'Tablet', width: 834, height: 1112 },
    { id: 'mobile', name: 'Mobile', width: 390, height: 844 },
]

const presetOptions = presets.map(p => ({ label: `${p.name} (${p.width}×${p.height})`, value: p.id }))
presetOptions.push({ label: 'Custom…', value: 'custom' })

const selectedPreset = ref(props.initialPreset)
const width = ref(presets.find(p => p.id === selectedPreset.value)?.width || 1440)
const height = ref(presets.find(p => p.id === selectedPreset.value)?.height || 900)
const zoom = ref(100)

const localFontset = ref(props.fontset)
const localColorset = ref(props.colorset)

watch(selectedPreset, id => {
    if (id === 'custom') return
    const p = presets.find(p => p.id === id)
    if (p) {
        width.value = p.width
        height.value = p.height
    }
})

const query = computed(() => {
    const params = new URLSearchParams()
    if (localFontset.value) params.set('fontset', localFontset.value)
    if (localColorset.value) params.set('colorset', localColorset.value)
    return params.toString()
})

const computedUrl = computed(() => {
    const base = props.path.endsWith('/') ? props.path.slice(0, -1) : props.path
    const q = query.value
    return q ? `${base}?${q}` : base
})

const iframeUrl = ref(computedUrl.value)

const frameWrap = ref(null)
const iframeRef = ref(null)

const outerStyle = computed(() => {
    const w = width.value
    const h = height.value
    const scale = Math.max(0.1, zoom.value / 100)
    return {
        width: w + 'px',
        height: h + 'px',
        transform: `scale(${scale})`,
        transformOrigin: 'top center',
        margin: '0 auto',
    }
})

const frameStyle = computed(() => ({ width: '100%', height: '100%' }))

function rotate() {
    const w = width.value
    width.value = height.value
    height.value = w
}

function reload() {
    iframeUrl.value = computedUrl.value
}

const copied = ref(false)
async function copyUrl() {
    try {
        await navigator.clipboard.writeText(computedUrl.value)
        copied.value = true
        setTimeout(() => (copied.value = false), 1200)
    } catch (e) {
        console.warn('Clipboard not available', e)
    }
}
</script>

<style scoped>
.frame-wrap {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px;
    background: repeating-conic-gradient(#f3f4f6 0% 25%, transparent 0% 50%) 50% / 20px 20px;
    overflow: auto;
}

.frame-outer {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.frame {
    display: block;
    border: 0;
}

.url-bar .mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
