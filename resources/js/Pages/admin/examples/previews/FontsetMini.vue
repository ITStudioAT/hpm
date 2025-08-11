<template>
    <div ref="host" class="mini"></div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'

const props = defineProps<{ value: string }>()
const host = ref<HTMLDivElement | null>(null)
let shadow: ShadowRoot | null = null

function renderShadow(slug: string) {
    if (!host.value) return
    // init / reset shadow
    if (!shadow) shadow = host.value.attachShadow({ mode: 'open' })
    shadow.innerHTML = ''

    // link the fontset css (scoped inside shadow)
    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = `/api/css/fontset/${encodeURIComponent(slug)}.css`
    shadow.appendChild(link)

    // local styles to layout the preview
    const style = document.createElement('style')
    style.textContent = `
    .wrap { display:grid; gap:4px; }
    .Aa { font-size: 22px; line-height:1; }
    .sample { font-size: 12px; opacity:.9; }
  `
    shadow.appendChild(style)

    // markup using your font classes
    const wrap = document.createElement('div')
    wrap.className = 'wrap'
    wrap.innerHTML = `
    <div class="Aa heroTitle">Aa</div>
    <div class="sample content">Grumpy wizards make toxic brew.</div>
    <div class="sample subcontent">1234567890 — !?@€%&</div>
  `
    shadow.appendChild(wrap)
}

onMounted(() => renderShadow(props.value || 'default'))
watch(() => props.value, v => renderShadow(v || 'default'))
</script>

<style scoped>
.mini {
    display: inline-block;
}
</style>
