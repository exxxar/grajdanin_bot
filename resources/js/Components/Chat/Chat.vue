<template>

    <div class="chat-page">

        <!-- HEADER -->

        <div class="chat-header">

            <RouterLink
                to="/chats"
                class="btn btn-light border"
            >
                ← К диалогам
            </RouterLink>

        </div>

        <!-- MESSAGES -->

        <div
            class="messages py-5"
            ref="messagesBox"
        >

            <template v-if="messages.length">

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

                                <a
                                    :href="a"
                                    target="_blank"
                                >
                                    {{ a }}
                                </a>

                            </div>

                        </div>

                        <div class="time">
                            {{ formatTime(m.created_at) }}
                        </div>

                    </div>

                </div>

            </template>

            <template v-else>

                <div class="empty-placeholder">
                    Сообщений пока нет
                </div>

            </template>

        </div>

        <!-- INPUT -->

        <div class="input-box">

            <!-- FILES -->

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

            <!-- INPUT GROUP -->

            <div class="message-group">

                <!-- ATTACH -->

                <label class="attach-btn">

                    📎

                    <input
                        type="file"
                        multiple
                        hidden
                        @change="handleFiles"
                    >

                </label>

                <!-- INPUT -->

                <div class="form-floating message-floating">

                    <input
                        id="messageInput"
                        v-model="text"
                        type="text"
                        class="form-control"
                        placeholder="Введите сообщение"
                        @keyup.enter="send"
                    >

                    <label for="messageInput">
                        Введите сообщение
                    </label>

                </div>

                <!-- SEND -->

                <button
                    class="send-btn"
                    @click="send"
                >
                    ➤
                </button>

            </div>

        </div>

    </div>

</template>

<script>
import {useMessagesStore} from '@/stores/messages'

export default {

    name: 'ChatPage',

    data() {

        return {
            text: '',
            files: []
        }

    },

    computed: {

        messages() {
            return useMessagesStore().messages
        },

        chatId() {
            return this.$route.params.id
        }

    },

    async mounted() {

        await useMessagesStore().loadChat(this.chatId)

        this.scrollBottom()

        this.$watch(
            () => this.messages,
            () => this.scrollBottom(),
            {deep: true}
        )

    },

    methods: {

        formatTime(date) {

            return new Date(date).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            })

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

            formData.append('chat_id', this.chatId)
            formData.append('text', this.text)
            formData.append('type', 'user')

            this.files.forEach(file => {
                formData.append('attachments[]', file)
            })

            await store.sendMessage(formData)

            this.text = ''
            this.files = []

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

/* =========================
   PAGE
========================= */

.chat-page {
    height: 100dvh;

    display: flex;
    flex-direction: column;

    overflow: hidden;
}

/* =========================
   HEADER
========================= */

.chat-header {
    padding: 12px;
    /* border-bottom: 1px solid #eee; */
    /* background: white; */
    position: sticky;
    top: 67px;
    z-index: 100;
    display: inline-block;
}

/* =========================
   MESSAGES
========================= */

.messages {
    flex: 1;

    overflow-y: auto;

    padding: 16px;

    display: flex;
    flex-direction: column;
}

.empty-placeholder {
    flex: 1;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #999;
}

/* =========================
   MESSAGE
========================= */

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

    border-radius: 16px;

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

/* =========================
   ATTACHMENTS
========================= */

.attachments {
    margin-top: 10px;
}

.attachment + .attachment {
    margin-top: 6px;
}

.attachment a {
    color: inherit;
    text-decoration: underline;
}

/* =========================
   INPUT
========================= */

.input-box {
    padding: 10px;

    border-top: 1px solid #ddd;

    background: white;


    position: fixed;
    bottom: 0px;
    z-index: 100;
    width: 100%;
}

.message-group {
    display: flex;
    align-items: stretch;

    gap: 8px;
}

/* =========================
   FLOATING INPUT
========================= */

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

/* =========================
   ATTACH BUTTON
========================= */

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

/* =========================
   SEND BUTTON
========================= */

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

/* =========================
   FILES
========================= */

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

/* =========================
   MOBILE
========================= */

@media (max-width: 768px) {

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

    .input-box {
        padding:
            calc(8px + env(safe-area-inset-bottom));
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

        font-size: 16px;

        border-radius: 12px;
    }

}

</style>
