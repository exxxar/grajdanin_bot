<template>
    <div class="p-3">

        <!-- LIST GROUP -->
        <ul class="list-group mb-3">
            <li class="list-group-item list-group-item-action"
                :class="{ active: activeForm === 'report' }"
                @click="activeForm = 'report'">
                📄 Базовый отчёт
            </li>

            <li class="list-group-item list-group-item-action"
                :class="{ active: activeForm === 'result' }"
                @click="activeForm = 'result'">
                ✅ Итоговый отчёт
            </li>

            <li class="list-group-item list-group-item-action"
                :class="{ active: activeForm === 'event' }"
                @click="activeForm = 'event'">
                🎉 Заявка на мероприятие
            </li>
        </ul>

        <!-- FORMS -->
        <div v-if="activeForm === 'report'">
            <ReportForm />
        </div>

        <div v-if="activeForm === 'result'">
            <ResultReportForm />
        </div>

        <div v-if="activeForm === 'event'">
            <EventRequestForm />
        </div>

    </div>
</template>

<script>
// Импортируй свои формы
import ReportForm from './ReportForm.vue'
import ResultReportForm from './ResultReportForm.vue'
import EventRequestForm from './EventRequestForm.vue'

export default {
    name: "ReportList",

    components: {
        ReportForm,
        ResultReportForm,
        EventRequestForm
    },

    data() {
        return {
            activeForm: null
        }
    }
}
</script>

<style scoped>
.list-group-item {
    cursor: pointer;
}
.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}
</style>
