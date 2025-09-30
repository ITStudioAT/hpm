import { defineStore } from 'pinia'

import { useAdminStore } from '@/stores/admin/AdminStore'
import { useNotificationStore } from '@/stores/spa/NotificationStore'

export const useFolderStore = defineStore('AdminFolderStore', {
    state: () => ({
        isSaving: false,
        active_folder: null,
        delete_count: 0,
        selected_action: '',
        folders: [],
        active_folder: null,
        folder_id: null,
    }),

    actions: {
        async move(homepage, folder_id, move_action, page_to_move_id, from_folder, to_folder) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/folders/move', {
                    homepage_id: homepage.id,
                    folder_id: folder_id,
                    move_action: move_action,
                    page_id: page_to_move_id,
                    from_folder: from_folder,
                    to_folder: to_folder,
                })

                const updated = response.data

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Seite/n wurde/n verschoben.',
                    type: 'success',
                    timeout,
                })
                return updated
            } catch (error) {
                const status = error?.response?.status ?? 500
                const message = error?.response?.data?.message ?? 'Fehler beim Aktualisieren.'

                this.error = { status, message, is_error: true, is_success: false }

                notification.notify({ status, message, type: 'error', timeout })
                return false
            } finally {
                this.is_loading = false
                this.isSaving = false
                adminStore.is_loading--
            }
        },
        async update(homepage, folder_id, path, data = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/folders/update', {
                    homepage_id: homepage.id,
                    folder_id: folder_id,
                    data: data,
                    path: path,
                })
                const updated = response.data

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Ordner wurde aktualisiert.',
                    type: 'success',
                    timeout,
                })
                return updated
            } catch (error) {
                const status = error?.response?.status ?? 500
                const message = error?.response?.data?.message ?? 'Fehler beim Aktualisieren.'

                this.error = { status, message, is_error: true, is_success: false }

                notification.notify({ status, message, type: 'error', timeout })
                return false
            } finally {
                this.is_loading = false
                this.isSaving = false
                adminStore.is_loading--
            }
        },
        async store(homepage, folder_id, path, data = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/folders', {
                    homepage_id: homepage.id,
                    path: path,
                    folder_id: folder_id,
                    data: data,
                })
                const created = response.data

                this.reload++
                this.error = {
                    status: null,
                    message: null,
                    is_error: false,
                    is_success: true,
                }

                notification.notify({
                    status: response.status,
                    message: 'Ordner wurde erstellt.',
                    type: 'success',
                    timeout,
                })

                return created
            } catch (error) {
                const status = error?.response?.status ?? 500
                const message = error?.response?.data?.message ?? 'Fehler passiert.'

                this.item = null
                this.api_answer = null
                this.error = {
                    status,
                    message,
                    is_error: true,
                    is_success: false,
                }

                notification.notify({
                    status,
                    message,
                    type: 'error',
                    timeout,
                })

                return false
            } finally {
                this.is_loading = false
                this.isSaving = false
                adminStore.is_loading--
            }
        },

        async destroy(homepage, folder_id, path) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/folders/destroy', {
                    homepage_id: homepage.id,
                    path: path,
                    folder_id: folder_id,
                })
                const created = response.data

                this.reload++
                this.error = {
                    status: null,
                    message: null,
                    is_error: false,
                    is_success: true,
                }

                notification.notify({
                    status: response.status,
                    message: 'Ordner wurde gelöscht.',
                    type: 'success',
                    timeout,
                })

                return created
            } catch (error) {
                const status = error?.response?.status ?? 500
                const message = error?.response?.data?.message ?? 'Fehler passiert.'

                this.item = null
                this.api_answer = null
                this.error = {
                    status,
                    message,
                    is_error: true,
                    is_success: false,
                }

                notification.notify({
                    status,
                    message,
                    type: 'error',
                    timeout,
                })

                return false
            } finally {
                this.is_loading = false
                this.isSaving = false
                adminStore.is_loading--
            }
        },

        async index(homepage_id = null, type) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.get('/api/admin/folders', {
                    params: { homepage_id: homepage_id, type: type },
                })
                const folders = response.data.structure.folders

                this.folders = folders
                this.folder_id = response.data.id

                if (!this.active_folder && this.folders.length > 0) this.active_folder = this.folders[0]

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                return folders
            } catch (error) {
                const status = error?.response?.status ?? 500
                const message = error?.response?.data?.message ?? 'Fehler passiert.'

                this.item = null
                this.api_answer = null
                this.error = { status, message, is_error: true, is_success: false }

                notification.notify({
                    status,
                    message,
                    type: 'error',
                    timeout,
                })

                return false
            } finally {
                this.is_loading = false
                this.isSaving = false
                adminStore.is_loading--
            }
        },
    },
})
