import { defineStore } from 'pinia'
import { makeAxiosFactory } from '../utillites/makeAxiosFactory'
import { useAlertStore } from '../utillites/useAlertStore'

export interface Report {
    id: number
    type: number
    from_user_id?: number | null
    to_user_id: number
    municipality_id: number
    received_at: string
    documents?: any[] | null
    created_at?: string
    updated_at?: string
}

const path = '/bot-api/reports'

export const useReportsStore = defineStore('reports', {
    state: () => ({
        items: [] as Report[],
        loading: false,
        error: null as string | null,
        pagination: null as any
    }),
    getters: {
        byId: (s) => (id: number) => s.items.find(r => r.id === id),
    },
    actions: {
        async fetchAll() {
            this.loading = true
            try {
                const { data } = await makeAxiosFactory(path, 'GET')
                this.items = data.data ?? data
                this.pagination = data
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки отчётов'
            } finally {
                this.loading = false
            }
        },
        async fetchOne(id: number) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
            return data as Report
        },
        async create(payload: Omit<Report, 'id'>) {
            const { data } = await makeAxiosFactory(path, 'POST', payload)
            this.items.push(data)
            return data as Report
        },
        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(r => r.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as Report
        },
        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(r => r.id !== id)
            useAlertStore().show(`Отчёт #${id} удалён`)
        }
    }
})
