import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null
    }),

    actions: {
        async init() {
            const res = await axios.post('/api/auth/init')
            this.user = res.data.user
            this.token = res.data.token
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        },

        async upgrade(data) {
            const res = await axios.post('/api/auth/upgrade', data)
            this.user = res.data.user
        }
    }
})
