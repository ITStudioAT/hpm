<template>
    <v-container fluid class="pa-4">
        <h2 class="mb-4">Beispiele zu Farben und Schriften</h2>
        <div class="text-grey text-body-2 mb-2">
            Links: Desktop, rechts: Handy
        </div>

        <v-row class="flex-wrap" align="start" no-gutters>
            <v-col class="pa-2">
                <div class="frame frame--desktop">
                    <iframe :src="src" :key="src + '-desktop'" title="Desktop Preview" />
                </div>
            </v-col>
            <v-col class="pa-2">
                <div class="frame frame--mobile">
                    <iframe :src="src" :key="src + '-mobile'" title="Mobile Preview" />
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps<{
    colorset?: string
    fontset?: string
}>()

const emit = defineEmits<{
    (e: 'update:colorset', v: string): void
    (e: 'update:fontset', v: string): void
}>()

// Build iframe URL with initial selection (toolbar remains visible in iframe)
const makeSrc = (c?: string, f?: string) => {
    const params = new URLSearchParams()
    if (c) params.set('colorset', c)
    if (f) params.set('fontset', f)
    params.set('t', String(Date.now())) // cache-bust on prop change
    return `/admin/color-fontset-preview?${params.toString()}`
}

const src = computed(() => makeSrc(props.colorset || 'default', props.fontset || 'default'))

// Listen to Blade -> parent messages and forward to the parent component
function onMessage(e: MessageEvent) {
    // Optional hardening:
    // if (e.origin !== window.location.origin) return;

    const d = (e?.data || {}) as { type?: string; colorset?: string; fontset?: string }
    if (d.type === 'color-fontset-selection') {
        if (typeof d.colorset === 'string') emit('update:colorset', d.colorset)
        if (typeof d.fontset === 'string') emit('update:fontset', d.fontset)
    }
}

onMounted(() => window.addEventListener('message', onMessage))
onBeforeUnmount(() => window.removeEventListener('message', onMessage))

// If parent changes props (e.g., loading another homepage), the computed `src` updates automatically
watch(() => [props.colorset, props.fontset], () => {
    /* no-op: computed handles src refresh */
})
</script>

<style scoped>
.frame {
    border: 2px solid #2f2a4a;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 10px 0 #d9d6f0;
}

.frame iframe {
    display: block;
    width: 100%;
    height: 560px;
    border: 0;
}

.frame--desktop {
    width: 1200px;
}

.frame--mobile {
    width: 400px;
}

@media (max-width: 1400px) {

    .frame--desktop,
    .frame--mobile {
        width: min(100%, 1200px);
    }
}
</style>
