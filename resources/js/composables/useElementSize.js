// composables/useElementSize.js
import { ref, onMounted, onBeforeUnmount } from 'vue'

export function useElementSize(target) {
    const width = ref(0)
    const height = ref(0)

    let observer

    onMounted(() => {
        if (target.value) {
            observer = new ResizeObserver(([entry]) => {
                width.value = entry.contentRect.width
                height.value = entry.contentRect.height
            })
            observer.observe(target.value)
        }
    })

    onBeforeUnmount(() => {
        if (observer && target.value) {
            observer.unobserve(target.value)
        }
    })

    return { width, height }
}
