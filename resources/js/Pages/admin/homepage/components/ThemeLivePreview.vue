<template>
    <iframe ref="frame"
        :style="{ width: width + 'px', height: height + 'px', border: '1px solid rgba(0,0,0,.12)', background: '#fff' }" />
</template>

<script setup>
import { ref, watch, onMounted, nextTick, computed } from 'vue'
import { createApp, h } from 'vue'

/* ✅ Vuetify for the iframe app */
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { VApp } from 'vuetify/components'

const props = defineProps({
    component: { type: Object, required: true },      // e.g. ThemeLiveRaw
    componentProps: { type: Object, default: () => ({}) },
    width: { type: Number, default: 1200 },
    height: { type: Number, default: 760 },
    force: { type: Number, default: 0 },             // (from earlier step)
})

const frame = ref(null)
let app = null

function copyBaseStyles(doc) {
    // copy base CSS (Vuetify, resets, icons) but SKIP theme links
    document.querySelectorAll('link[rel="stylesheet"], style').forEach(n => {
        const href = n.getAttribute?.('href') || ''
        const id = n.id || ''
        if (
            id === 'colorset-css' || id === 'fontset-css' ||
            href.includes('/api/css/colors/') || href.includes('/api/css/fontset/')
        ) return
        doc.head.appendChild(n.cloneNode(true))
    })
}

async function mountInIframe() {
    const el = frame.value
    if (!el) return
    const doc = el.contentDocument || el.contentWindow?.document

    doc.open()
    doc.write(`<!doctype html><html><head>
    <meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/>
    <style>html,body,#app{height:100%;margin:0}</style>
  </head><body><div id="app"></div></body></html>`)
    doc.close()

    copyBaseStyles(doc)

    // ⬇️ Create a fresh Vuetify instance for this iframe app
    const vuetify = createVuetify({ components, directives })

    // ⬇️ Wrap your component with <v-app> (Vuetify needs it)
    const Root = {
        render: () => h(VApp, null, [h(props.component, props.componentProps)])
    }

    app = createApp(Root)
    app.use(vuetify)
    app.mount(doc.getElementById('app'))
}

function unmountFromIframe() {
    if (app) { app.unmount(); app = null }
}

onMounted(async () => { await nextTick(); await mountInIframe() })

/* Remount when these change (no deep-watch thrash) */
const signature = computed(() => JSON.stringify({
    w: props.width, h: props.height,
    cs: props.componentProps?.colorset,
    fs: props.componentProps?.fontset,
    f: props.force,
}))
watch([() => props.component, signature], async () => {
    unmountFromIframe()
    await nextTick()
    await mountInIframe()
})
</script>
