<template>
    <div class="mini">
        <div class="dots" :title="value">
            <div class="dot" :style="{ background: vars.background || '#ccc' }"></div>
            <div class="dot" :style="{ background: vars.main || '#ccc' }"></div>
            <div class="dot" :style="{ background: vars.secondary || '#ccc' }"></div>
            <div class="dot" :style="{ background: vars.tertiary || '#ccc' }"></div>
            <div class="dot" :style="{ background: vars.highlight || '#ccc' }"></div>
            <div class="dot" :style="{ background: vars.button || '#ccc' }"></div>
        </div>
        <div class="bars" aria-hidden="true">
            <div class="bar" :style="{ background: vars.background || '#ccc' }"></div>
            <div class="bar" :style="{ background: vars.main || '#ccc' }"></div>
            <div class="bar" :style="{ background: vars.secondary || '#ccc' }"></div>
            <div class="bar" :style="{ background: vars.tertiary || '#ccc' }"></div>
            <div class="bar" :style="{ background: vars.highlight || '#ccc' }"></div>
            <div class="bar" :style="{ background: vars.button || '#ccc' }"></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, watch } from 'vue'

const props = defineProps<{ value: string }>()
const vars = reactive<Record<string, string>>({})

async function loadVars(slug: string) {
    if (!slug) return
    try {
        const r = await fetch(`/api/css/colors/${encodeURIComponent(slug)}.css`, {
            headers: { Accept: 'text/css' },
        })
        const css = await r.text()
        const re = /--([\w-]+)\s*:\s*([^;]+);/g
        let m: RegExpExecArray | null
        const temp: Record<string, string> = {}
        while ((m = re.exec(css)) !== null) temp[m[1]] = m[2].trim()
        // keep just the ones we care about
        vars.background = temp.background
        vars.main = temp.main
        vars.secondary = temp.secondary
        vars.tertiary = temp.tertiary
        vars.highlight = temp.highlight
        vars.button = temp.button
    } catch (e) {
        // swallow; keep defaults
    }
}

onMounted(() => loadVars(props.value || 'default'))
watch(() => props.value, v => loadVars(v || 'default'))
</script>

<style scoped>
.mini {
    display: grid;
    gap: 6px;
}

.dots {
    display: flex;
    gap: 6px;
    align-items: center;
}

.dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, .2);
}

.bars {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px;
}

.bar {
    height: 16px;
    border: 1px solid rgba(0, 0, 0, .15);
}
</style>
