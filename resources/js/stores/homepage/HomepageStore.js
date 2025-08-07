import { defineStore } from 'pinia'
import { useNotificationStore } from "@/stores/spa/NotificationStore";

function ensureFontUrlLoaded(href) {
    if (!href) return;
    const id = 'dynamic-font-css';
    const existing = document.getElementById(id);

    if (existing) {
        // only update if href actually changed
        if (existing.getAttribute('href') !== href) {
            existing.setAttribute('href', href);
            existing.dataset.fontsetHref = href;
        }
        return;
    }

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.id = id;
    link.href = href;
    link.dataset.fontsetHref = href;
    document.head.appendChild(link);
}

export const useHomepageStore = defineStore("HomepageStore", {
    state: () => ({
        config: null,
        is_loading: 0,
        error: { is_error: false, status: null, message: null, timeout: 3000 },
        homepage: null,
        fontTitle: '',
        fontSubtitle: '',
        fontContent: '',
        fontSubcontent: '',
    }),

    actions: {


        async loadHomepage(preview = null) {
            const notification = useNotificationStore();
            this.is_loading++;
            try {
                const { data } = await axios.get("/api/homepage/load_homepage", { params: { preview } });

                // homepage resource
                this.homepage = data?.data;

                // font info (from controller meta)
                const fontType = data?.meta?.fontset || this.homepage?.structure?.fonts?.fontType || 'default';
                const v = data?.meta?.fontVersion || Date.now(); // dev-safe fallback

                // load cacheable CSS via <link> with version for cache-busting
                ensureFontUrlLoaded(`/fontset/${fontType}.css?v=${encodeURIComponent(v)}`);

                return true;
            } catch (error) {
                notification.notify({
                    status: error?.response?.status ?? 500,
                    message: error?.response?.data?.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: 3000,
                });
                return false;
            } finally {
                this.is_loading--;
            }
        }

    },
})
