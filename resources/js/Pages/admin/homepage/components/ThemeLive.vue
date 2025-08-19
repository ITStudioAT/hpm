<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
    colorset: { type: String, default: 'default' },
    fontset: { type: String, default: 'default' },
})

const root = ref(null)

function ensureStylesheet(id, href, doc) {
    return new Promise((res) => {
        let link = doc.getElementById(id)
        if (!link) {
            link = doc.createElement('link')
            link.id = id
            link.rel = 'stylesheet'
            doc.head.appendChild(link)
        }
        if (link.getAttribute('href') === href) return res(link)
        link.addEventListener('load', () => res(link), { once: true })
        link.addEventListener('error', () => res(link), { once: true })
        link.setAttribute('href', href)
    })
}

const bust = (href) => href + (href.includes('?') ? '&' : '?') + 'v=' + Date.now()

async function applyTheme() {
    const doc = root.value?.ownerDocument || document   // 👈 iframe’s document
    await ensureStylesheet('fonts-core', '/fonts/fonts.css', doc) // static
    await ensureStylesheet('fontset-css', bust(`/api/css/fontset/${encodeURIComponent(props.fontset)}.css`), doc)
    await ensureStylesheet('colorset-css', bust(`/api/css/colors/${encodeURIComponent(props.colorset)}.css`), doc)
    await ensureStylesheet('color-bindings', '/css/colors.css', doc)  // static
}

onMounted(applyTheme)
watch(() => [props.colorset, props.fontset], applyTheme)
</script>

<template>
    <!-- (optional) key forces a cheap repaint after swap -->
    <div ref="root" :key="colorset + '|' + fontset" class="theme-live background" :data-fontset="fontset"
        :data-colorset="colorset" :class="['fontset', `fontset-${fontset}`, fontset]">
        <!-- your content ... -->
        <div class="heroTitle">HeroTitle</div>
        <div class="heroLead">HeroLead</div>

        <div class="first">
            <div class="heroTitle">HeroTitle bg-first</div>
            <div class="heroLead">HeroLead bg-first</div>
        </div>

        <div class="second">
            <div class="heroTitle">HeroTitle bg-second</div>
            <div class="heroLead">HeroLead bg-second</div>
        </div>

        <div class="third">
            <div class="heroTitle">HeroTitle bg-second</div>
            <div class="heroLead">HeroLead bg-second</div>
        </div>
    </div>
</template>
