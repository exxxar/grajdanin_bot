import { defineStore } from 'pinia'
import { makeAxiosFactory } from '../utillites/makeAxiosFactory'

export interface IncomingReport {
    id: number
    report_id: number
    received_from?: string | null
    problem_description: string
    help_formats?: any[] | null
    comment?: string | null
    problems?: Record<string, string[]> | null
    solutions?: Record<string, string[]> | null
    difficulties?: Record<string, string[]> | null
    audio_files?: any[] | null
    created_at?: string
    updated_at?: string
    report?: {
        id: number
        phone?: string
        received_at?: string
        municipality_id?: number
    }
}

export interface IncomingReportCreateResult extends IncomingReport {
    chat_id?: number
    report_url?: string
}

const path = '/api/reports/incoming'

export const useIncomingReportsStore = defineStore('incomingReports', {
    state: () => ({
        items: [] as IncomingReport[],
        loading: false,
        error: null as string | null,
        pagination: null as any
    }),
    getters: {
        byId: (s) => (id: number) => s.items.find(r => r.id === id),
    },
    actions: {
        async fetchMine() {
            this.loading = true
            try {
                const { data } = await makeAxiosFactory(`${path}/mine`, 'GET')
                this.items = data.data ?? data
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки заявок'
            } finally {
                this.loading = false
            }
        },
        async fetchAll() {
            this.loading = true
            try {
                const { data } = await makeAxiosFactory(path, 'GET')
                this.items = data.data ?? data
                this.pagination = data
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки входящих отчётов'
            } finally {
                this.loading = false
            }
        },
        async fetchOne(id: number) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
            return data as IncomingReport
        },
        async createFromForm(formData: FormData): Promise<IncomingReportCreateResult> {
            const { data } = await makeAxiosFactory(path, 'POST', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            })
            const item = (data.data ?? data) as IncomingReport
            const result: IncomingReportCreateResult = {
                ...item,
                chat_id: data.chat_id,
                report_url: data.report_url,
            }
            this.items.unshift(result)
            return result
        },
        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(r => r.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as IncomingReport
        },
        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(r => r.id !== id)
        }
    }
})
