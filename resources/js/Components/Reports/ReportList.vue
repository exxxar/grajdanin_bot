<template>
    <div class="p-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0">Список отчётов</h5>
            <button class="btn btn-primary btn-sm" @click="$emit('create')">
                ➕ Новый отчёт
            </button>
        </div>

        <!-- LIST GROUP -->
        <ul class="list-group mb-3">
            <li v-for="report in paginated"
                :key="report.id"
                class="list-group-item list-group-item-action"
                @click="$emit('open', report)">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">
                            Отчёт #{{ report.id }}
                        </div>
                        <div class="text-muted small">
                            Тип: {{ report.type }} · Получен: {{ report.received_at }}
                        </div>
                        <div class="small">
                            Муниципалитет: {{ report.municipality_id }}
                        </div>
                    </div>

                    <div class="ms-3 text-end">
                        <button class="btn btn-outline-primary btn-sm me-1"
                                @click.stop="$emit('edit', report)">
                            ✎
                        </button>
                        <button class="btn btn-outline-danger btn-sm"
                                @click.stop="remove(report.id)">
                            🗑
                        </button>
                    </div>
                </div>

            </li>

            <li v-if="paginated.length === 0"
                class="list-group-item text-center text-muted py-4">
                Нет данных
            </li>
        </ul>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-outline-secondary btn-sm"
                    :disabled="page === 1"
                    @click="page--">
                Назад
            </button>

            <span class="small">Стр. {{ page }} из {{ totalPages }}</span>

            <button class="btn btn-outline-secondary btn-sm"
                    :disabled="page === totalPages"
                    @click="page++">
                Далее
            </button>
        </div>

    </div>
</template>

<script>
export default {
    name: "ReportList",

    data() {
        return {
            page: 1,
            perPage: 5,

            // Заглушка данных (заменится API)
            reports: [
                { id: 1, type: "Базовый", municipality_id: "101", received_at: "2026-01-10" },
                { id: 2, type: "Итоговый", municipality_id: "102", received_at: "2026-01-11" },
                { id: 3, type: "Мероприятие", municipality_id: "103", received_at: "2026-01-12" },
                { id: 4, type: "Базовый", municipality_id: "104", received_at: "2026-01-13" },
                { id: 5, type: "Итоговый", municipality_id: "105", received_at: "2026-01-14" },
                { id: 6, type: "Мероприятие", municipality_id: "106", received_at: "2026-01-15" }
            ]
        };
    },

    computed: {
        totalPages() {
            return Math.ceil(this.reports.length / this.perPage);
        },

        paginated() {
            const start = (this.page - 1) * this.perPage;
            return this.reports.slice(start, start + this.perPage);
        }
    },

    methods: {
        remove(id) {
            if (!confirm("Удалить отчёт #" + id + "?")) return;
            this.reports = this.reports.filter(r => r.id !== id);

            if (this.page > this.totalPages) {
                this.page = this.totalPages || 1;
            }
        }
    }
};
</script>

<style scoped>
.list-group-item {
    cursor: pointer;
    border-radius: 8px;
    margin-bottom: 8px;
}
.list-group-item-action:hover {
    background-color: #f8f9fa;
}
</style>
