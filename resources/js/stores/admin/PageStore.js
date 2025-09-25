import { defineStore } from 'pinia'
import { createResourceStore } from './ResourceStore'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useNotificationStore } from '@/stores/spa/NotificationStore'

const resourceStore = createResourceStore('pages')

export const usePageStore = defineStore('AdminPageStore', {
    state: () => ({
        ...resourceStore.state(),
        isSaving: false,
        active_page: null,
        delete_action: 0,
        selected_action: '',
    }),

    actions: {
        ...resourceStore.actions(),

        async store(payload = {}) {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.post('/api/admin/pages', payload)
                const created = response.data

                this.active_page = created

                this.reload++
                this.error = {
                    status: null,
                    message: null,
                    is_error: false,
                    is_success: true,
                }

                notification.notify({
                    status: response.status,
                    message: 'Seite wurde erstellt.',
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

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const id = data?.id
                if (id == null || id === '') {
                    throw { response: { status: 422, data: { message: 'data.id is required' } } }
                }

                const response = await axios.put(`/api/admin/pages/${encodeURIComponent(id)}`, data)
                const updated = response.data

                // set active + upsert in list
                this.active_page = updated
                if (Array.isArray(this.pages)) {
                    const idx = this.pages.findIndex((h) => h?.id === id)
                    if (idx !== -1) this.pages.splice(idx, 1, updated)
                    else this.pages.push(updated)
                }

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Seite wurde aktualisiert.',
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

        async index() {
            const adminStore = useAdminStore()
            const notification = useNotificationStore()
            const timeout = adminStore.config?.timeout ?? this.timeout ?? 3000

            this.is_loading = true
            this.isSaving = true
            adminStore.is_loading++

            try {
                const response = await axios.get('/api/admin/pages', {})
                const pages = response.data

                this.pages = pages

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                return pages
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
                const response = await axios.delete(`/api/admin/pages/${data?.id}`, {})

                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Page wurde gelöscht.',
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
