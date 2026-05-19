<template>
    <div class="container py-4">

        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Мои отчёты</h4>

            <button class="btn btn-primary btn-sm" disabled>
                <i class="fa-solid fa-plus"></i> Создать отчёт
            </button>
        </div>

        <!-- Заглушка -->
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">

                <i class="fa-solid fa-file-lines text-secondary mb-3" style="font-size: 48px;"></i>

                <h5 class="text-muted">Раздел в разработке</h5>

                <p class="text-muted small mb-4">
                    Здесь будут отображаться ваши отчёты, статистика и история обращений.
                </p>

                <button class="btn btn-outline-secondary btn-sm" disabled>
                    <i class="fa-solid fa-rotate"></i> Обновить
                </button>

            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'UserReportsPage',

    data() {
        return {}
    },

    mounted() {
        // Здесь позже будет загрузка данных
    }
}
</script>
