<script setup>
import { ref, onMounted, watch, onBeforeUnmount, nextTick } from 'vue'

const props = defineProps({
    colorset: { type: String, default: 'default' },
    fontset: { type: String, default: 'default' },
})

const root = ref(null)
let alive = true
onBeforeUnmount(() => { alive = false })

const bust = (u) => u + (u.includes('?') ? '&' : '?') + 'v=' + Date.now()
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

let runId = 0
async function applyTheme() {
    const myRun = ++runId
    await nextTick()                           // ensure ref is populated
    if (!alive || !root.value) return

    const doc = root.value.ownerDocument || document

    await ensureStylesheet('fonts-core', '/fonts/fonts.css', doc)
    await ensureStylesheet('fontset-css', bust(`/api/css/fontset/${encodeURIComponent(props.fontset)}.css`), doc)
    await ensureStylesheet('colorset-css', bust(`/api/css/colors/${encodeURIComponent(props.colorset)}.css`), doc)
    await ensureStylesheet('color-bindings', '/css/colors.css', doc)

    // let styles apply
    await new Promise(r => requestAnimationFrame(r))
    if (!alive || myRun !== runId || !root.value) return

    // Preload font weights (safe guards)
    if ('fonts' in doc) {
        const famProbe = () => {
            const el = doc.createElement('div')
            el.className = 'content'
            el.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none;'
            el.textContent = 'A'
            doc.body.appendChild(el)
            const fam = doc.defaultView.getComputedStyle(el).fontFamily || ''
            el.remove()
            return fam
        }
        const fam = famProbe().split(',')[0].replace(/['"]/g, '').trim()
        const weights = ['400', '600', '700'] // adjust if your fontset differs
        try {
            await Promise.allSettled(weights.map(w => doc.fonts.load(`normal ${w} 16px "${fam}"`)))
            await doc.fonts.ready
        } catch { }
    }

    // (Optional) cheap repaint — only if root still exists
    if (!alive || myRun !== runId || !root.value) return
    // root.value.style.willChange = 'contents'
    // void root.value.offsetHeight
    // root.value.style.willChange = ''
}

onMounted(applyTheme)
watch(() => [props.colorset, props.fontset], applyTheme)
</script>


<template>
    <!-- optional: cheap repaint after switch -->
    <div ref="root" class="theme-live background" :data-fontset="fontset" :data-colorset="colorset"
        :class="['fontset', `fontset-${fontset}`, fontset]">
        <v-container fluid class="background">
            <div class="ma-4 main">
                <v-row no-gutters="">
                    <v-col cols="12" md="6">
                        <div class="d-flex flex-row justify-center w-100 mt-2">
                            <div style=" width:80%;" class="mt-md-16">
                                <div class="heroTitle">Entspannen & Entdecken</div>
                                <div class="heroLead mt-4">Raus aus der Komfortzone, rein ins echte Leben. Abenteuer
                                    umarmen, Unbekanntes probieren und dabei die eigene Stärke entdecken. Jeder Tag eine
                                    Einladung, mehr zu werden</div>
                            </div>
                        </div>

                        <div class="d-flex flex-row justify-center w-100 mt-16 first">
                            <div style=" width:80%;" class="py-6">
                                <div class="title">Entspannen & Entdecken</div>
                                <div class="subtitle mt-4">Raus aus der Komfortzone, rein ins echte Leben. Abenteuer
                                    umarmen, Unbekanntes probieren und dabei die eigene Stärke entdecken. Jeder Tag eine
                                    Einladung, mehr zu werden</div>
                            </div>
                        </div>

                        <div class="d-flex flex-row justify-center w-100 mt-8 ">
                            <div style=" width:80%;" class="py-6">
                                <div class="content">Loslassen, losziehen, losleben. Mit jedem Schritt mutiger,
                                    mit jeder Erfahrung reicher. Das
                                    Unentdeckte ruft – und wir antworten mit Tatendrang.</div>
                            </div>
                        </div>



                    </v-col>
                    <v-col cols="12" md="6">
                        <div class="pa-2">
                            <v-img src="/storage/images/motiv.jpg" alt="No picture" contain />
                        </div>
                    </v-col>
                </v-row>

                <v-row no-gutters="">
                    <v-col cols="12" md="6">

                        <div class="d-flex flex-row justify-center w-100 mt-16 second">
                            <div style=" width:80%;" class="py-6">
                                <div class="title">Entspannen & Entdecken</div>
                                <div class="subtitle mt-4">Raus aus der Komfortzone, rein ins echte Leben. Abenteuer
                                    umarmen, Unbekanntes probieren und dabei die eigene Stärke entdecken. Jeder Tag eine
                                    Einladung, mehr zu werden</div>
                            </div>
                        </div>

                        <div class="d-flex flex-row justify-center w-100 mt-8 ">
                            <div style=" width:80%;" class="py-6">
                                <div class="content">Loslassen, losziehen, losleben. Mit jedem Schritt mutiger,
                                    mit jeder Erfahrung reicher. Das
                                    Unentdeckte ruft – und wir antworten mit Tatendrang.</div>
                            </div>
                        </div>



                    </v-col>
                    <v-col cols="12" md="6">

                        <div class="d-flex flex-row justify-center w-100 mt-16 third">
                            <div style=" width:80%;" class="py-6">
                                <div class="title">Entspannen & Entdecken</div>
                                <div class="subtitle mt-4">Raus aus der Komfortzone, rein ins echte Leben. Abenteuer
                                    umarmen, Unbekanntes probieren und dabei die eigene Stärke entdecken. Jeder Tag eine
                                    Einladung, mehr zu werden</div>
                            </div>
                        </div>

                        <div class="d-flex flex-row justify-center w-100 mt-8 ">
                            <div style=" width:80%;" class="py-6">
                                <div class="content">Loslassen, losziehen, losleben. Mit jedem Schritt mutiger,
                                    mit jeder Erfahrung reicher. Das
                                    Unentdeckte ruft – und wir antworten mit Tatendrang.</div>
                            </div>
                        </div>
                    </v-col>
                </v-row>



            </div>
        </v-container>

    </div>
</template>
