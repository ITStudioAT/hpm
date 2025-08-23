import { defineStore } from 'pinia'

export const useMediamanagerStore = defineStore("MMMediamanagerStore", {

    state: () => {
        return {
            is_loading: 0,
            is_loading_preview: 0,
            current_folder: null,
            parent_folders: [],
            folders: [],
            files: [],
            preview_files: [],
            selected_files: [],
            notification: {
                show: false,
                message: '',
                status: null,
                type: '',
                timeout: 3000,
            }


        }
    },

    actions: {


        async saveFilename(data) {
            this.is_loading++;
            try {
                const response = await axios.post("/api/mediamanager/save_filename", { data });
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading--;
            }
        },

        async destroyFiles(files, path = null) {
            this.is_loading++;
            try {

                const response = await axios.post("/api/mediamanager/destroy_files", { files, path });
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading--;
            }
        },

        async destroyFolder(path) {
            this.is_loading++;
            try {

                const response = await axios.post("/api/mediamanager/destroy_folder", { path });
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading--;
            }
        },


        async createFolder(path, name) {
            this.is_loading++;
            try {
                const response = await axios.post("/api/mediamanager/create_folder", { path, name });
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading--;
            }
        },


        async folderStructure(path = null) {
            this.is_loading++;
            try {

                const response = await axios.get("/api/mediamanager/folder_structure", { params: { path } });
                this.folders = []; this.files = [];
                this.folders = response.data?.folders;
                this.files = response.data?.files;
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading--;
            }
        },


        async createPreview(path = null) {
            this.is_loading_preview++;
            try {

                const response = await axios.get("/api/mediamanager/create_preview", { params: { path } });
                this.preview_files = response.data;
            } catch (error) {
                this.pushError(error);
            } finally {
                this.is_loading_preview--;
            }
        },

        pushError(error) {
            this.notification.message = error.response.data.message;
            this.notification.status = error.response.status;
            this.notification.type = "error";
            this.notification.show = true;
        }




    }
})

