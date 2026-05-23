<script setup>
import ReportCard from "@/Components/Reports/ReportCard.vue";
import ReportListCard from "@/Components/Reports/ReportListCard.vue";
</script>

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
                class="list-group-item list-group-item-action p-0"
            >
                <ReportListCard
                    :item="item"
                    @open-chat="openChat"
                    @open-details="goToDetails"
                />
            </div>




        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="report-details" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Детали по отчету #{{selected?.id||'-'}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template v-if="selected">
                        <ReportCard
                            :item="selected"></ReportCard>
                    </template>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Закрыть</button>
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
            selected: null,
            store: useIncomingReportsStore(),
        }
    },

    mounted() {
        this.reload()
    },

    methods: {
        goToDetails(item){
            this.selected = null
            this.$nextTick(()=>{
                this.selected = item

                const modal = new bootstrap.Modal(document.getElementById('report-details'), {})
                modal.show()
            })

        },
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
        openChat(report) {
            this.$router.push({ name: 'ChatPage', query: { report: report.id } })
        },
    },
}
</script>
