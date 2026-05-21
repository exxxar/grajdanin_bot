<template>

    <template v-if="tab==='main'">
        <!-- Список диалогов -->
        <div class="chat-list">

            <template v-if="chats.length">

                <h3>Диалоги</h3>

                <!-- Bootstrap List -->
                <div class="list-group list-group-flush">

                    <button
                        v-for="c in chats"
                        :key="c.id"
                        type="button"
                        class="list-group-item list-group-item-action text-start"
                        :class="{ active: c.id === chatId }"
                        @click="openChat(c.id)"
                    >

                        <div class="d-flex w-100 justify-content-between align-items-center">

                            <h6 class="mb-1">
                                Чат №{{ c.id }}
                            </h6>

                        </div>

                        <small class="opacity-75">
                            Заявка №{{ c.report_id || c.report?.id || '—' }}
                            <span v-if="c.report?.incoming_report?.received_from">
                                · {{ c.report.incoming_report.received_from }}
                            </span>
                        </small>

                    </button>

                </div>

            </template>

            <!-- Empty -->
            <template v-else>

                <div class="d-flex h-100 align-items-center justify-content-center p-4 text-center text-muted">

                    <div>
                        <div class="fs-2 mb-2">
                            💬
                        </div>

                        <div>
                            У вас еще нет сообщений
                        </div>
                    </div>

                </div>

            </template>

        </div>
    </template>

    <template v-if="tab==='chat'">
        <div class="chat-layout">



            <!-- Правая часть -->
            <div class="chat-window">

                <!-- Если чат выбран -->
                <template v-if="chatId">

                    <div class="messages" ref="messagesBox">

                        <div
                            v-for="m in messages"
                            :key="m.id"
                            class="message"
                            :class="m.type"
                        >
                            <div class="bubble">

                                <div v-if="m.text">
                                    {{ m.text }}
                                </div>

                                <div
                                    v-if="m.attachments?.length"
                                    class="attachments"
                                >
                                    <div
                                        v-for="(a, i) in m.attachments"
                                        :key="i"
                                        class="attachment"
                                    >
                                        <a :href="a" target="_blank">
                                            {{ a }}
                                        </a>
                                    </div>
                                </div>

                                <div class="time">
                                    {{ new Date(m.created_at).toLocaleTimeString() }}
                                </div>

                            </div>
                        </div>

                    </div>

                </template>

                <!-- Если чатов нет -->
                <template v-else>

                    <div class="messages empty-messages">
                        <div class="empty-placeholder">
                            Начните новый диалог
                        </div>
                    </div>

                </template>

                <div class="input-box">

                    <!-- Файлы -->
                    <div
                        v-if="files.length"
                        class="files-preview"
                    >
                        <div
                            v-for="(file, index) in files"
                            :key="index"
                            class="file-chip"
                        >
                            {{ file.name }}

                            <span @click="removeFile(index)">
                ✕
            </span>
                        </div>
                    </div>

                    <!-- Группа ввода -->
                    <div class="message-group">

                        <!-- Кнопка файла -->
                        <label class="attach-btn">
                            📎

                            <input
                                type="file"
                                multiple
                                @change="handleFiles"
                                hidden
                            >
                        </label>

                        <!-- Float label -->
                        <div class="form-floating message-floating">

                            <input
                                v-model="text"
                                type="text"
                                class="form-control"
                                id="messageInput"
                                placeholder="Введите сообщение"
                                @keyup.enter="send"
                            >

                            <label for="messageInput">
                                Введите сообщение
                            </label>

                        </div>

                        <!-- Кнопка -->
                        <button
                            class="send-btn"
                            @click="send"
                        >
                            ➤
                        </button>

                    </div>

                </div>

            </div>

        </div>
    </template>

</template>

<script>
import { useMessagesStore } from '@/stores/messages'
import { useAuthStore } from '@/stores/auth.js'

export default {
    name: 'Chat',

    props: {
        reportId: {
            type: Number,
            default: null
        }
    },

    data() {
        return {
            tab:'main',
            text: '',
            files: []
        }
    },

    computed: {
        user() {
            return useAuthStore().user
        },

        chats() {
            return useMessagesStore().chats
        },

        messages() {
            return useMessagesStore().messages
        },

        chatId() {
            return useMessagesStore().chatId
        }
    },

    watch: {
        user: {
            immediate: true,

            async handler(user) {
                if (!user) return

                const store = useMessagesStore()
                await store.fetchChats()

                if (this.reportId) {
                    const chat = store.chats.find(
                        (c) => c.report_id === this.reportId || c.report?.id === this.reportId
                    )
                    if (chat) {
                        await store.loadChat(chat.id)
                        this.tab = 'chat'
                    }
                }
            }
        }
    },

    mounted() {

        this.$watch(
            () => this.messages,
            () => this.scrollBottom(),
            { deep: true }
        )
    },

    methods: {

        async openChat(id) {
            const store = useMessagesStore()

            await store.loadChat(id)

            this.tab = "chat"
            this.scrollBottom()
        },

        handleFiles(event) {

            const selected = Array.from(event.target.files)

            this.files.push(...selected)
        },

        removeFile(index) {

            this.files.splice(index, 1)
        },

        async send() {

            if (!this.text.trim() && !this.files.length) {
                return
            }

            const store = useMessagesStore()

            const formData = new FormData()

            if (this.reportId) {
                formData.append('report_id', this.reportId)
            } else if (store.chatId) {
                const chat = store.chats.find((c) => c.id === store.chatId)
                if (chat?.report_id) {
                    formData.append('report_id', chat.report_id)
                }
            }

            if (store.chatId) {
                formData.append('chat_id', store.chatId)
            }

            formData.append('text', this.text)
            formData.append('type', 'user')

            this.files.forEach(file => {
                formData.append('attachments[]', file)
            })

            await store.sendMessage(formData)

            this.text = ''
            this.files = []

            await store.fetchChats()

            this.scrollBottom()
        },

        scrollBottom() {

            this.$nextTick(() => {

                const box = this.$refs.messagesBox

                if (box) {
                    box.scrollTop = box.scrollHeight
                }

            })
        }
    }
}
</script>

<style scoped>

.chat-layout {
    display: flex;
    height: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

/* Левая колонка */

.chat-list {
    width: 280px;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    background: #fafafa;
}

.chat-list-header {
    padding: 14px;
    font-weight: bold;
    border-bottom: 1px solid #eee;
}

.chat-item {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    transition: .2s;
}

.chat-item:hover {
    background: #f3f3f3;
}

.chat-item.active {
    background: #47c2c0;
    color: white;
}

.chat-title {
    font-weight: bold;
}

.chat-sub {
    margin-top: 4px;
    font-size: 13px;
    opacity: .8;
}

.chat-empty {
    padding: 20px;
    color: #777;
    text-align: center;
}

/* Правая часть */

.chat-window {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
}

.empty-messages {
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-placeholder {
    color: #888;
    font-size: 15px;
}

/* Сообщения */

.message {
    margin-bottom: 12px;
    display: flex;
}

.message.user {
    justify-content: flex-end;
}

.message.admin,
.message.system {
    justify-content: flex-start;
}

.bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    background: #ececec;
    word-break: break-word;
}

.message.user .bubble {
    background: #47c2c0;
    color: white;
}

.time {
    margin-top: 6px;
    font-size: 11px;
    opacity: .7;
}

/* Ввод */

.input-box {
    display: flex;
    gap: 8px;
    padding: 12px;
    border-top: 1px solid #ddd;
}

.input-box input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
}

.input-box button {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    background: #47c2c0;
    color: white;
    cursor: pointer;
}

.input-box button:hover {
    opacity: .9;
}

/* =========================
   MOBILE
========================= */

@media (max-width: 768px) {

    .chat-layout {
        flex-direction: column;
        height: auto;
        border-radius: 0;
    }

    /* Список чатов */

    .chat-list {
        width: 100%;
        height: 100vh;
        max-height: 100vh;
        border-right: none;
        border-bottom: 1px solid #ddd;
        overflow-x: auto;
        overflow-y: hidden;
        display: flex;
        align-items: stretch;
        padding: 10px;
        gap: 10px;
    }

    .chat-list-header {
        display: none;
    }

    .chat-item {
        min-width: 220px;
        max-width: 220px;
        border: 1px solid #eee;
        border-radius: 12px;
        background: white;
        flex-shrink: 0;
        margin: 0;
    }

    .chat-item.active {
        background: #47c2c0;
        color: white;
        border-color: #47c2c0;
    }

    .chat-title {
        font-size: 14px;
    }

    .chat-sub {
        font-size: 12px;
    }

    .chat-empty {
        width: 100%;
        padding: 20px 10px;
        text-align: center;
        font-size: 14px;
    }

    /* Окно чата */

    .chat-window {
        flex: 1;
        min-height: 0;
    }

    .messages {
        padding: 12px;
    }

    .bubble {
        max-width: 88%;
        font-size: 14px;
        padding: 10px 12px;
    }

    .time {
        font-size: 10px;
    }

    /* Поле ввода */

    .input-box {
        padding: 10px;
        gap: 6px;
        background: white;
        position: sticky;
        bottom: 0;
    }

    .input-box input {
        font-size: 16px; /* фикс zoom на iOS */
        padding: 10px 12px;
    }

    .input-box button {
        white-space: nowrap;
        padding: 10px 14px;
        font-size: 14px;
    }

}

/* =========================
   INPUT GROUP
========================= */

.input-box {
    padding: 10px;
    border-top: 1px solid #ddd;
    background: white;

    display: flex;
    flex-wrap: wrap;

    position: fixed;
}

.message-group {
    display: flex;
    align-items: stretch;
    gap: 8px;
}

/* FLOATING */

.message-floating {
    flex: 1;
}

.message-floating .form-control {
    height: 58px;
    border-radius: 14px;
    padding-left: 14px;
}

.message-floating label {
    padding-left: 14px;
}

/* ATTACH */

.attach-btn {
    width: 58px;
    min-width: 58px;
    border-radius: 14px;
    border: 1px solid #ced4da;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: white;
    font-size: 20px;
    transition: .2s;
}

.attach-btn:hover {
    background: #f5f5f5;
}

/* SEND */

.send-btn {
    width: 58px;
    min-width: 58px;
    border: none;
    border-radius: 14px;
    background: #47c2c0;
    color: white;
    font-size: 20px;
    transition: .2s;
}

.send-btn:hover {
    opacity: .9;
}

/* FILES */

.files-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 10px;
}

.file-chip {
    background: #f1f1f1;
    border-radius: 20px;
    padding: 6px 10px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.file-chip span {
    cursor: pointer;
    font-weight: bold;
}

/* MOBILE */

@media (max-width: 768px) {

    .input-box {
        padding: 8px;
    }

    .message-group {
        gap: 6px;
    }

    .attach-btn,
    .send-btn {
        width: 50px;
        min-width: 50px;
        border-radius: 12px;
        font-size: 18px;
    }

    .message-floating .form-control {
        height: 50px;
        font-size: 16px; /* iOS zoom fix */
        border-radius: 12px;
    }

}
</style>
