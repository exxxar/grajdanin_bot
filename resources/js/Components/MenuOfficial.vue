<script setup>

</script>
<template>
    <button type="button"
            @click="goTo('SalePage')"
            class="btn btn-primary p-3 mb-2 w-100">Профиль официального лица</button>

    <div class="btn-group-vertical w-100" role="group" aria-label="Вертикальное меню действий">

        <button type="button"
                data-bs-toggle="modal" :data-bs-target="'#newSaleModal'"
                class="btn btn-outline-primary p-3">Список всех отчетов</button>
        <button type="button"
                data-bs-toggle="modal" :data-bs-target="'#reportModal'"
                class="btn btn-outline-primary p-3">Список заявок на мероприятия</button>
        <button type="button"
                data-bs-toggle="modal" :data-bs-target="'#reportModal'"
                class="btn btn-outline-primary p-3">Список обращений граждан</button>

    </div>


    <!-- Модалка создания -->
    <div class="modal fade" id="newSaleModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Создание задания</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <SaleForm @saved="fetchData"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Модалка -->
    <div class="modal fade" :id="'reportModal'" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Генератор отчетов</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ReportGenerator @generate-report="handleReport"></ReportGenerator>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
        methods:{
            goTo(name) {
                this.$router.push({ name: name})

            },
            handleReport(payload) {

                const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'))
                modal.hide()
                console.log('Отчёт сформирован:', payload)
                // тут можно вызвать API или Pinia store
            },
            async fetchData() {
                bootstrap.Modal.getInstance(document.getElementById('newSaleModal')).hide()
            },
        }
}
</script>
