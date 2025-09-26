import { defineStore } from 'pinia'
import { createResourceStore } from './ResourceStore'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useNotificationStore } from '@/stores/spa/NotificationStore'

const resourceStore = createResourceStore('homepages')

export const useHomepageStore = defineStore('AdminHomepageStore', {
    state: () => ({
        ...resourceStore.state(),
        isSaving: false,
        active_homepage: null,
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
                const response = await axios.post('/api/admin/homepages', payload)
                const created = response.data

                this.active_homepage = created

                this.reload++
                this.error = {
                    status: null,
                    message: null,
                    is_error: false,
                    is_success: true,
                }

                notification.notify({
                    status: response.status,
                    message: 'Homepage wurde erstellt.',
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

                const response = await axios.put(`/api/admin/homepages/${encodeURIComponent(id)}`, data)
                const updated = response.data

                // set active + upsert in list
                this.active_homepage = updated

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Homepage wurde aktualisiert.',
                    type: 'success',
                    timeout,
                })

                return updated
            } catch (error) {
                this.index()
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
                const response = await axios.get('/api/admin/homepages', {})
                const homepages = response.data

                this.homepages = homepages

                this.reload++
                this.error = { status: null, message: null, is_error: false, is_success: true }

                return homepages
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
                const response = await axios.delete(`/api/admin/homepages/${data?.id}`, {})

                this.error = { status: null, message: null, is_error: false, is_success: true }

                notification.notify({
                    status: response.status,
                    message: 'Homepage wurde gelöscht.',
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
