import { defineStore } from 'pinia'
import { createResourceStore } from './ResourceStore'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useNotificationStore } from '@/stores/spa/NotificationStore'

const resourceStore = createResourceStore('menus')

export const useMenuStore = defineStore('AdminMenuStore', {
    state: () => ({
        ...resourceStore.state(),
        isSaving: false,
        active_menu: null,
        delete_action: 0,
        selected_action: '',
    }),

    actions: {
        ...resourceStore.actions(),

        async store(homepage, data = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/menus', { homepage_id: homepage.id, data: data })
                const created = response.data

                this.active_menu = created

                this.reload++
                this.error = {
                    status: null,
                    message: null,
                    is_error: false,
                    is_success: true,
                }

                notification.notify({
                    status: response.status,
                    message: 'Menü wurde erstellt.',
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

        async update(data = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000
            console.log('updating...')
            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const id = data?.id
                if (id == null || id === '') {
                    throw { response: { status: 422, data: { message: 'data.id is required' } } }
                }

                const response = await axios.put(`/api/admin/menus/${encodeURIComponent(id)}`, data)
                const updated = response.data

                // set active + upsert in list
                this.active_menu = updated

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Menü wurde aktualisiert.',
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

        async index(homepage_id = null) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.get('/api/admin/menus', { params: { homepage_id: homepage_id } })
                const menus = response.data

                this.menus = menus

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                return menus
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

        async delete(data = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.delete(`/api/admin/menus/${data?.id}`, {})

                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Menü wurde gelöscht.',
                    type: 'success',
                    timeout,
                })

                return true
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
    },
})
