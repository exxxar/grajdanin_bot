import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

export const useMessagesStore = defineStore('messages', {
    state: () => ({
        chats: [],
        chatId: null,
        messages: [],
        loading: false
    }),

    actions: {
        // Устанавливаем токен в axios
        applyToken() {
            const auth = useAuthStore()
            if (auth.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`
            }
        },

        // Получить список чатов пользователя
        async fetchChats() {
            this.applyToken()

            const res = await axios.get('/api/chats')
            this.chats = res.data
        },

        // Загрузить сообщения выбранного чата
        async loadChat(chatId) {
            this.applyToken()

            this.chatId = chatId
            const res = await axios.get(`/api/chat/${chatId}/messages`)
            this.messages = res.data
        },

        // Отправить сообщение (создаст чат, если chat_id нет)
        async sendMessage(payload) {
            this.applyToken()

            const res = await axios.post('/api/messages', payload)

            // Если чат только что создан — сохраняем chat_id
            if (!this.chatId) {
                this.chatId = res.data.chat_id
                await this.fetchChats()
            }

            this.messages.push(res.data.message)
        }
    }
})
