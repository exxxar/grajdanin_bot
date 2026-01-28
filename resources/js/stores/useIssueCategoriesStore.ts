import { defineStore } from 'pinia'
import { makeAxiosFactory } from './utillites/makeAxiosFactory'
import { useAlertStore } from './utillites/useAlertStore'

export interface IssueCategory {
    id: number
    name: string
    type: number
    description?: string | null
    icon?: string | null
    variants?: any[] | null
    created_at?: string
    updated_at?: string
}

const path = '/bot-api/issue-categories'

export const useIssueCategoriesStore = defineStore('issueCategories', {
    state: () => ({
        items: [] as IssueCategory[],
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
                this.error = e?.message || 'Ошибка загрузки категорий'
            } finally {
                this.loading = false
            }
        },

        async fetchOne(id: number) {
            try {
                const { data } = await makeAxiosFactory(`${path}/${id}`, 'GET')
                return data as IssueCategory
            } catch (e: any) {
                this.error = e?.message || 'Ошибка загрузки категории'
                throw e
            }
        },

        async create(payload: Omit<IssueCategory, 'id'>) {
            const { data } = await makeAxiosFactory(path, 'POST', payload)
            this.items.push(data)
            return data as IssueCategory
        },

        async update(id: number, payload: object) {
            const { data } = await makeAxiosFactory(`${path}/${id}`, 'PUT', payload)
            const idx = this.items.findIndex(i => i.id === id)
            if (idx !== -1) this.items[idx] = data
            return data as IssueCategory
        },

        async remove(id: number) {
            await makeAxiosFactory(`${path}/${id}`, 'DELETE')
            this.items = this.items.filter(i => i.id !== id)

            const alertStore = useAlertStore()
            alertStore.show(`Категория #${id} удалена`)
        }
    }
})
