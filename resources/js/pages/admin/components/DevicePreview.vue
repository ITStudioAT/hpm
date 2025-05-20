<template>
    <v-container class="pa-4">
        <v-row align="center" class="mb-4">
            <v-col cols="12" md="4">
                <v-select v-model="selectedDevice" :items="deviceOptions" label="Select device" item-title="label"
                    item-value="key" variant="outlined" dense />
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="12" class="d-flex justify-center">
                <v-card :style="deviceStyle" class="overflow-hidden elevation-10" rounded="sm">
                    <iframe :src="previewUrl" class="w-100 h-100" frameborder="0" :title="selectedDevice"></iframe>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref, computed } from 'vue'

const selectedDevice = ref('desktop')
const previewUrl = '/' // Change this to the route or page you want to preview

const baseDevices = {
    desktop: { label: 'Desktop', width: 1280, height: 800 },
    tabletPortrait: { label: 'Tablet hoch', width: 768, height: 1024 },
    tabletLandscape: { label: 'Tablet quer', width: 1024, height: 768 },
    phonePortrait: { label: 'Phone hoch', width: 375, height: 667 },
    phoneLandscape: { label: 'Phone quer', width: 667, height: 375 },
}

const deviceOptions = Object.entries(baseDevices).map(([key, value]) => ({
    key,
    label: value.label,
}))

const currentDevice = computed(() => baseDevices[selectedDevice.value])

const deviceStyle = computed(() => {
    const { width, height } = currentDevice.value
    return {
        width: `${width}px`,
        height: `${height}px`,
        border: '1px solid #ccc',
        background: '#fff',
    }
})
</script>

<style scoped>
iframe {
    border: none;
}
</style>
