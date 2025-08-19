<template>
    <iframe ref="frame" :style="{
        width: width + 'px',
        height: height + 'px',
        border: '1px solid rgba(0,0,0,.12)',
        background: '#fff'
    }" />
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { createApp, h } from 'vue'

const props = defineProps({
    component: { type: Object, required: true },     // e.g. ThemeLive
    componentProps: { type: Object, default: () => ({}) },
    width: { type: Number, default: 1200 },
    height: { type: Number, default: 760 },
})

const frame = ref(null)
let app = null

function copyHostStyles(doc) {
    document.querySelectorAll('link[rel="stylesheet"], style').forEach(n => {
        const href = n.getAttribute?.('href') || ''
        const id = n.id || ''
        if (
            id === 'colorset-css' || id === 'fontset-css' ||
            href.includes('/api/css/colors/') || href.includes('/api/css/fontset/')
        ) {
            return  // skip theme links; ThemeLive will inject fresh ones
        }
        doc.head.appendChild(n.cloneNode(true))
    })
}


function mountInIframe() {
    const el = frame.value
    if (!el) return

    const doMount = () => {
        const doc = el.contentDocument || el.contentWindow?.document
        if (!doc) return
        doc.open()
        doc.write(`<!doctype html>
      <html><head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <style>html,body,#app{height:100%;margin:0}</style>
      </head><body><div id="app"></div></body></html>`)
        doc.close()

        copyHostStyles(doc)

        app = createApp({ render: () => h(props.component, props.componentProps) })
        app.mount(doc.getElementById('app'))
    }

    // wait until iframe is ready
    if (el.contentDocument?.readyState === 'complete') doMount()
    else el.addEventListener('load', doMount, { once: true })
}

function unmountFromIframe() {
    if (app) { app.unmount(); app = null }
}

onMounted(async () => {
    await nextTick()
    mountInIframe()
})
onBeforeUnmount(() => { unmountFromIframe() })

watch(
    () => [props.component, props.componentProps, props.width, props.height],
    () => { unmountFromIframe(); mountInIframe() },
    { deep: true }
)
</script>
