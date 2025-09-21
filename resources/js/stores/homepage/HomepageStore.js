import { defineStore } from 'pinia'
import { useNotificationStore } from "@/stores/spa/NotificationStore";

export const useHomepageStore = defineStore("HomepageStore", {

    state: () => {
        return {
            router: null,
            config: null,
            is_loading: 0,
            error: {
                is_error: false,
                status: null,
                message: null,
                timeout: 3000,
            },
            colorDefs: {
                "--bg-all": "#3ba3ad",
                "--text-all": "#FFFFFF",

                "--color-0": "#FFFFFF",
                "--color-1": "#DDDDDD",
                "--color-2": "#f582ae",
                "--color-3": "#8bd3dd",

                "--text-0": "#0b333d",
                "--text-hl": "#000000",

                "--appbar-bg-4": "#000000",
                "--appbar-text-4": "#DDDDDD",
                "--appbar-bg-5": "#0b333d",
                "--appbar-text-5": "#FFFFFF",
            },
            fontset: null,
            cf_sets: [],

        }
    },

    actions: {
        initialize(router) {
            this.router = router;
        },

        async config(router) {
            this.is_loading++;
            try {
                const response = await axios.get("/api/homepage/config", {});
                this.config = response.data;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },


        async colorset(colorset = 'default') {
            const notification = useNotificationStore();
            this.is_loading++;
            try {
                const response = await axios.get("/api/homepage/colorset", { params: { colorset: colorset } });
                this.colorDefs = response.data.colorDefs;


            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: 3000,
                });
            } finally {
                this.is_loading--;
            }
        },

        async fontset(fontset = 'default') {
            const notification = useNotificationStore();
            this.is_loading++;
            try {
                const response = await axios.get("/api/homepage/fontset", { params: { fontset: fontset } });
                this.fontset = response.data;


            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: 3000,
                });
            } finally {
                this.is_loading--;
            }
        },

        async loadSets() {
            const notification = useNotificationStore();
            this.is_loading++;
            try {
                const response = await axios.get("/api/homepage/load_sets", {});
                this.cf_sets = response.data;


            } catch (error) {
                notification.notify({
                    status: error.response.status,
                    message: error.response.data.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: 3000,
                });
            } finally {
                this.is_loading--;
            }
        },


        redirect(status, message, type) {
            const redirectUrl = '/application/error?status=' + status + '&message=' + encodeURIComponent(message) + '&type=' + type;
            window.location.href = redirectUrl; // This is a real redirect
        }



    }
})

