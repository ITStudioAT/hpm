// src/stores/admin/FontsetStore.js
import { defineStore } from 'pinia'
import { useAdminStore } from "@/stores/admin/AdminStore";
import { useNotificationStore } from "@/stores/spa/NotificationStore";

export const useFontsetStore = defineStore("AdminFontsetStore", {

    state: () => ({
        fontsets: [],
    }),

    getters: {
        // For <v-autocomplete> { value, label }
        options: (state) => state.fontsets.map(it => {
            const value = it.value ?? it.slug ?? it.id ?? it.code;
            const label = it.label ?? it.name ?? it.title ?? "";
            return { value, label: label || "(ohne Namen)" };
        }),
    },

    actions: {
        async loadFontsets(force = false) {
            const notification = useNotificationStore();
            const adminStore = useAdminStore();

            if (this.fontsets.length && !force) return true;

            adminStore.is_loading++;
            try {
                const res = await axios.get('/api/fontsets'); // <-- your route
                const data = res?.data;
                // accept either [] or { data: [] }
                this.fontsets = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
                return true;
            } catch (error) {
                notification.notify({
                    status: error?.response?.status,
                    message: error?.response?.data?.message || 'Fehler passiert.',
                    type: 'error',
                    timeout: adminStore.timeout,
                });
                return false;
            } finally {
                adminStore.is_loading--;
            }
        },
    },
});
