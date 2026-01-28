import { defineStore } from 'pinia'
import { makeAxiosFactory } from './utillites/makeAxiosFactory'
import { useAlertStore } from './utillites/useAlertStore'

export interface HelpFormat {
    id: number
    name: string
    config?: Record<string, any> | null
    created_at?: string
    updated_at?: string
}

const path = '/bot-api/help-formats'

export const useHelpFormatsStore = defineStore('helpFormats', {
    state: () => ({
        items: [] as HelpFormat[],
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
                this.error = e?.message || 'Ошибка загрузки типов помощи'
            } finally {
                this.loading = false
            }
        },

        async fetchOne(id: number) {
            try {
                const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
                return data as HelpFormat
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки типа помощи'
                throw e
            }
        },

        async create(payload: Omit<HelpFormat, 'id'>) {
            const { data } = await makeAxiosFactory(path, 'POST', payload)
            this.items.push(data)
            return data as HelpFormat
        },

        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(i => i.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as HelpFormat
        },

        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(i => i.id !== id)

            const alertStore = useAlertStore()
            alertStore.show(`Тип помощи #${id} удалён`)
        }
    }
})
