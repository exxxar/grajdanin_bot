import { defineStore } from 'pinia'
import axios from 'axios'




export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null
    }),

    actions: {
        async init() {
            axios.defaults.withCredentials = true

            await axios.get('/sanctum/csrf-cookie').then(()=>{
                axios.defaults.withXSRFToken = true

                const token = decodeURIComponent(
                    document.cookie
                        .split('; ')
                        .find(row => row.startsWith('XSRF-TOKEN='))
                        ?.split('=')[1]
                )


                const res = axios.post('/api/auth/init',{},{
                    withCredentials: true,
                    headers: {
                        'X-XSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                }).then(resp=>{
                    this.user = resp.data.user
                })


                //this.token = res.data.token
                //axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            })

        },
        async updateProfile(data) {
            const res = await axios.post('/api/auth/update-profile', data)
            this.user = res.data.user
        },
        async login(phone, password) {
            const res = await axios.post('/api/auth/login', { phone, password })

            this.user = res.data.user
            //this.token = res.data.token
            //axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        },

        async upgrade(data) {
            const res = await axios.post('/api/auth/upgrade', data)

            this.user = res.data.user
        },

        async logout() {
            await axios.post('/api/auth/logout')
            this.user = null
          //  this.token = null
            //delete axios.defaults.headers.common['Authorization']
        },

        async fetchMe() {
            const res = await axios.get('/api/auth/me')
            this.user = res.data.user
        }



    }
})
