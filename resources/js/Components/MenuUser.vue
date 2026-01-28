<script setup>
import IncomingReportForm from "@/Components/Reports/Forms/IncomingReportForm.vue";
import EventRequestForm from "@/Components/Reports/Forms/EventRequestForm.vue";
import VolunteerForm from "@/Components/Volunteers/VolunteerForm.vue";
</script>

<template>

    <template v-if="tab==='main'">
        <div class="btn-group-vertical w-100" role="group" aria-label="Вертикальное меню">
            <button type="button"
                    @click="tab='incoming-request'"
                    class="btn btn-outline-primary p-3">Жалоба на проблему</button>
            <button type="button"
                    @click="tab='event-request'"
                    class="btn btn-outline-primary p-3">Запрос на проведение мероприятия</button>
            <button type="button"
                    @click="tab='volunteer-form'"
                    class="btn btn-outline-primary p-3">Стать волонтером</button>
        </div>

    </template>

    <template v-if="tab==='incoming-request'">
        <button
            @click="tab='main'"
            class="btn btn-light text-secondary mb-3" style="position: sticky; top:80px; z-index: 100;">К меню</button>

        <IncomingReportForm></IncomingReportForm>
    </template>

    <template v-if="tab==='event-request'">
        <button
            @click="tab='main'"
            class="btn btn-light text-secondary mb-3" style="position: sticky; top:80px; z-index: 100;">К меню</button>
        <EventRequestForm></EventRequestForm>
    </template>

    <template v-if="tab==='volunteer-form'">
        <button
            @click="tab='main'"
            class="btn btn-light text-secondary mb-3" style="position: sticky; top:80px; z-index: 100;">К меню</button>
        <VolunteerForm></VolunteerForm>
    </template>



</template>
<script>
export default {
        data(){
          return {
              tab:"main",
          }
        },
        methods:{
            hideModal(modalId){
                const modalEl = document.getElementById(modalId)
                const modalInstance = bootstrap.Modal.getInstance(modalEl)
                modalInstance.hide()
            }
        }
}
</script>
