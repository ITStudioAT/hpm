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
            },
            homepage: null,
            fontTitle: '',
            fontSubtitle: '',
            fontContent: '',
            fontSubcontent: '',

        }
    },

    actions: {

        createFontClass(fontType) {
            if (fontType) {
                this.fontTitle = `${fontType}-responsive-title`;
                this.fontSubtitle = `${fontType}-responsive-subtitle`;
                this.fontContent = `${fontType}-responsive-content`;
                this.fontSubcontent = `${fontType}-responsive-subcontent`;
            }

        },

        async loadHomepage(preview = null) {
            const notification = useNotificationStore();
            this.is_loading++;
            try {
                const response = await axios.get("/api/homepage/load_homepage", {
                    params: { preview }
                });
                this.homepage = response.data;
                console.log(this.homepage.structure.fonts);
                this.createFontClass(this.homepage?.structure?.fonts?.fontType || null);

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

