<template>

    <div class="container py-3">

        <template v-if="chats.length">

            <h3 class="mb-3">
                Диалоги
            </h3>

            <div class="list-group list-group-flush">

                <RouterLink
                    v-for="c in chats"
                    :key="c.id"
                    :to="`/chats/${c.id}`"
                    class="list-group-item list-group-item-action rounded-4  bg-white border-light-subtle"
                >

                    <div class="d-flex justify-content-between">

                        <h6 class="mb-1">
                            Чат №{{ c.id }}
                        </h6>

                    </div>

                    <small class="text-muted">

                        Заявка №{{ c.report_id || c.report?.id || '—' }}

                        <span v-if="c.report?.incoming_report?.received_from">
                            · {{ c.report.incoming_report.received_from }}
                        </span>

                    </small>

                </RouterLink>

            </div>

        </template>

        <template v-else>

            <div class="text-center text-muted py-5">

                <div class="fs-1 mb-2">
                    💬
                </div>

                <div>
                    У вас еще нет сообщений
                </div>

            </div>

        </template>

    </div>

</template>

<script>
import {useMessagesStore} from '@/stores/messages'
import {useAuthStore} from '@/stores/auth'

export default {

    name: 'ChatsPage',

    computed: {

        chats() {
            return useMessagesStore().chats
        },

        user() {
            return useAuthStore().user
        }

    },

    watch: {

        user: {

            immediate: true,

            async handler(user) {

                if (!user) return

                await useMessagesStore().fetchChats()

            }

        }

    }

}
</script>
<style scoped>

.list-group-item {
    transition: .2s;
    border: 1px solid #eee;
    margin-bottom: 8px;
}

.list-group-item:hover {
    background: #f5f5f5;
}

</style>
