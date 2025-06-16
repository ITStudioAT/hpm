import { defineStore } from 'pinia'

export const useAdminStore = defineStore("HpmAdminStore", {

    state: () => {
        return {
            router: null,
            config: null,
            homepage: null,
            main: null,
            is_loading: 0,
            error: {
                is_error: false,
                status: null,
                message: null,
                timeout: 3000,
            },
            hpm: null,
            hp: null,

        }
    },

    actions: {
        initialize(router) {
            this.router = router;
        },

        async getConfig() {
            this.is_loading++;
            try {
                const response = await axios.get("/api/hpm/admin/get_config", {});
                this.config = response.data;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async getJson(source = null) {
            this.is_loading++;
            try {
                const response = await axios.get("/api/hpm/admin/get_json", { params: { source: source } });
                return response.data;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async saveJson(source, data) {
            this.is_loading++;
            try {
                const response = await axios.post("/api/hpm/admin/save_json", { source, data });

            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async getHpm(source) {
            this.is_loading++;
            try {
                const response = await axios.get("/api/hpm/admin/get_hpm", { params: { source: source } });
                this.hpm = response.data.hpm;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async saveHpm(source, data) {
            this.is_loading++;
            try {

                const response = await axios.post("/api/hpm/admin/save_hpm", { source, data });
                this.hpm = response.data.hpm;

            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
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

