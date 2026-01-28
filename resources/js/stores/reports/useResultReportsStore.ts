import { defineStore } from 'pinia'
import { makeAxiosFactory } from '../utillites/makeAxiosFactory'

export interface ResultReport {
    id: number
    report_id: number
    topic: string
    description?: string | null
    actions?: any[] | null
    result?: any[] | null
    difficulties?: any[] | null
    created_at?: string
    updated_at?: string
}

const path = '/bot-api/reports/result'

export const useResultReportsStore = defineStore('resultReports', {
    state: () => ({
        items: [] as ResultReport[],
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
                this.error = e?.message || 'Ошибка загрузки итоговых отчётов'
            } finally {
                this.loading = false
            }
        },
        async fetchOne(id: number) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
            return data as ResultReport
        },
        async create(payload: Omit<ResultReport, 'id'>) {
            const { data } = await makeAxiosFactory(path, 'POST', payload)
            this.items.push(data)
            return data as ResultReport
        },
        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(r => r.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as ResultReport
        },
        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(r => r.id !== id)
        }
    }
})
