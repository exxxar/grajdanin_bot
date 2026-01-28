<template>
    <div class="p-3">

        <h5 class="mb-3">Просмотр отчёта</h5>

        <div v-if="report" class="card">
            <div class="card-body">

                <ul class="list-group list-group-flush">

                    <li class="list-group-item">
                        <strong>ID:</strong> {{ report.id }}
                    </li>

                    <li class="list-group-item">
                        <strong>Тип:</strong> {{ report.type }}
                    </li>

                    <li class="list-group-item">
                        <strong>Гражданин:</strong> {{ report.from_user_id || '—' }}
                    </li>

                    <li class="list-group-item">
                        <strong>Официальное лицо:</strong> {{ report.to_user_id }}
                    </li>

                    <li class="list-group-item">
                        <strong>Муниципалитет:</strong> {{ report.municipality_id }}
                    </li>

                    <li class="list-group-item">
                        <strong>Дата получения:</strong> {{ report.received_at }}
                    </li>

                    <li class="list-group-item">
                        <strong>Документы:</strong>
                        <div v-if="report.documents && report.documents.length > 0">
                            <ul class="small mb-0 ps-3">
                                <li v-for="(doc, index) in report.documents" :key="index">
                                    {{ doc }}
                                </li>
                            </ul>
                        </div>
                        <span v-else class="text-muted">нет</span>
                    </li>

                    <li class="list-group-item">
                        <strong>Создан:</strong> {{ report.created_at }}
                    </li>

                    <li class="list-group-item">
                        <strong>Обновлён:</strong> {{ report.updated_at }}
                    </li>

                </ul>

            </div>
        </div>

        <div v-else class="text-muted text-center py-4">
            Отчёт не выбран
        </div>

        <div class="mt-3 d-flex justify-content-end">
            <button class="btn btn-outline-primary btn-sm me-2"
                    @click="$emit('edit', report)">
                ✎ Редактировать
            </button>
            <button class="btn btn-outline-danger btn-sm"
                    @click="$emit('delete', report)">
                🗑 Удалить
            </button>
        </div>

    </div>
</template>

<script>
export default {
    name: "ReportView",

    props: {
        report: {
            type: Object,
            default: null
        }
    }
}
</script>

<style scoped>
.card {
    border-radius: 8px;
}
.list-group-item {
    font-size: 14px;
}
</style>
