import { defineStore } from 'pinia'
import { useAdminStore } from "@/stores/admin/AdminStore";
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



export const useHomepageStore = defineStore("AdminHomepageStore", {

    state: () => ({
        homepages: [],
        homepage: null,
        record: null,
        homepage_copy: null,
        data: null,
    }),


    actions: {

        async loadHomepages() {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.get('/api/admin/homepage/index', {});
                this.homepages = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },

        async loadHomepage(id) {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const { data } = await axios.get('/api/admin/homepage/load_homepage', { params: { id } });
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
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },

        async loadRecord(homepage_id, record_id) {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.get('/api/admin/homepage/load_record', { params: { homepage_id, record_id } });
                this.record = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },

        async deleteHomepage(id) {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.post('/api/admin/homepage/delete', { id });
                // this.homepage = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },

        async saveHomepage(homepage) {

            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.post('/api/admin/homepage/save', { homepage });
                this.homepage = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },

        async saveRecord(record) {

            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.post('/api/admin/homepage/save_record', { record });
                this.record = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },


        async createHomepage() {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();
            adminStore.is_loading++;
            try {
                const response = await axios.post('/api/admin/homepage/create', {});
                this.homepage = response.data;
                return true;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        }
    }
})

