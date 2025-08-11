import { defineStore } from 'pinia'
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNotificationStore } from "@/stores/spa/NotificationStore";

export const useHomepageStore = defineStore("AdminHomepageStore", {

    state: () => ({
        homepages: [],
        homepage: null,
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
                const response = await axios.get('/api/admin/homepage/load_homepage', { params: { id } });
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

