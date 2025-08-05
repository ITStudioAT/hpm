import { defineStore } from 'pinia'
import { useNotificationStore } from "@/stores/spa/NotificationStore";

export const useHomepageStore = defineStore("HomepageStore", {

    state: () => {
        return {

            config: null,
            is_loading: 0,
            error: {
                is_error: false,
                status: null,
                message: null,
                timeout: 3000,
            }

        }
    },

    actions: {

        async loadHomepage() {
            return;
            const notification = useNotificationStore();
            this.is_loading++; this.api_response = null;
            try {
                this.api_response = await axios.get("/api/admin/config", {});
                this.config = this.api_response.data;
                return this.api_response.data;
            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: 3000,
                });
                return false;
            } finally {
                this.is_loading--;
            }
        },






    }
})

