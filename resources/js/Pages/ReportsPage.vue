<template>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Мои заявки</h4>
            <button class="btn btn-outline-secondary btn-sm" @click="reload" :disabled="store.loading">
                <i class="fa-solid fa-rotate"></i> Обновить
            </button>
        </div>

        <div v-if="store.loading" class="text-center text-muted py-5">
            Загрузка...
        </div>

        <div v-else-if="!store.items.length" class="card shadow-sm border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa-solid fa-file-lines mb-3" style="font-size: 48px;"></i>
                <h5>Заявок пока нет</h5>
                <p class="small mb-0">Создайте заявку в разделе «Жалоба на проблему» на главной.</p>
            </div>
        </div>

        <div v-else class="list-group list-group-flush">
            <div
                v-for="item in store.items"
                :key="item.id"
                class="list-group-item list-group-item-action p-3"
            >
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Заявка №{{ item.report_id }}</h6>
                        <p class="mb-1 small text-muted">
                            {{ item.received_from || 'Без имени' }}
                            <span v-if="item.report?.phone"> · {{ item.report.phone }}</span>
                        </p>
                        <p class="mb-0 small">
                            {{ truncate(item.problem_description, 120) }}
                        </p>
                        <small class="text-muted" v-if="item.created_at">
                            {{ formatDate(item.created_at) }}
                        </small>
                    </div>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm text-nowrap"
                        @click="openChat(item.report_id)"
                    >
                        В чат
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { useIncomingReportsStore } from '@/stores/reports/useIncomingReportsStore'

export default {
    name: 'UserReportsPage',

    data() {
        return {
            store: useIncomingReportsStore(),
        }
    },

    mounted() {
        this.reload()
    },

    methods: {
        async reload() {
            await this.store.fetchMine()
        },
        truncate(text, max) {
            if (!text) return ''
            return text.length > max ? text.slice(0, max) + '…' : text
        },
        formatDate(value) {
            return new Date(value).toLocaleString('ru-RU')
        },
        openChat(reportId) {
            this.$router.push({ name: 'ChatPage', query: { report: reportId } })
        },
    },
}
</script>
