import { defineStore } from 'pinia'
import { makeAxiosFactory } from './utillites/makeAxiosFactory'
import { useAlertStore } from './utillites/useAlertStore'

export interface Municipality {
    id: number
    name: string
    config?: Record<string, any> | null
    created_at?: string
    updated_at?: string
}

const path = '/bot-api/municipalities'

export const useMunicipalitiesStore = defineStore('municipalities', {
    state: () => ({
        items: [] as Municipality[],
        loading: false,
        error: null as string | null,
        pagination: null as any
    }),

    getters: {
        byId: (s) => (id: number) => s.items.find(i => i.id === id),
    },

    actions: {
        async fetchAll() {
            this.loading = true
            this.error = null
            try {
                const { data } = await makeAxiosFactory(path, 'GET')
                this.items = data.data ?? data
                this.pagination = data
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки муниципалитетов'
            } finally {
                this.loading = false
            }
        },

        async fetchOne(id: number) {
            try {
                const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
                return data as Municipality
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки муниципалитета'
                throw e
            }
        },

        async create(payload: Omit<Municipality, 'id'>) {
            const { data } = await makeAxiosFactory(path, 'POST', payload)
            this.items.push(data)
            return data as Municipality
        },

        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(i => i.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as Municipality
        },

        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(i => i.id !== id)

            const alertStore = useAlertStore()
            alertStore.show(`Муниципалитет #${id} удалён`)
        }
    }
})
