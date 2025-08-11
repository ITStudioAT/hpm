<template>
    <v-container fluid class="pa-4">
        <h2 class="mb-4">Beispiele zu Farben und Schriften</h2>

        <div class="text-grey text-body-2 mb-2">
            Links: „Desktop“-Breite, rechts: „Handy“-Breite — beide laden denselben Fontset, reagieren aber
            eigenständig.
        </div>

        <v-row class="flex-wrap" align="start" no-gutters>
            <v-col class="pa-2">
                <div class="frame frame--desktop">
                    <iframe :src="previewSrc(active)" title="Desktop Preview" />
                </div>
            </v-col>
            <v-col class="pa-2">
                <div class="frame frame--mobile">
                    <iframe :src="previewSrc(active)" title="Mobile Preview" />
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const sets = ['default', 'education', 'nobel', 'manual'] as const
const active = ref<typeof sets[number]>('default')

const previewSrc = (fontset: string) =>
    `/admin/color-fontset-preview?fontset=${encodeURIComponent(fontset)}&t=${Date.now()}`

const sLabel = (s: string) => s.charAt(0).toUpperCase() + s.slice(1)
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