import { defineStore } from 'pinia'

export const useHomepageStore = defineStore("HpmHomepageStore", {

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
            }

        }
    },

    actions: {
        initialize(router) {
            this.router = router;
        },

        elementById(id) {
            return this.homepage.find(item => item.id === id);
        },

        async getConfig() {
            this.is_loading++;
            try {
                const response = await axios.get("/api/hpm/homepage/get_config", {});
                this.config = response.data;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async loadHomepage() {
            this.is_loading++;
            try {
                const response = await axios.get("/api/hpm/homepage/load_homepage", {});
                this.homepage = response.data.homepage;
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

