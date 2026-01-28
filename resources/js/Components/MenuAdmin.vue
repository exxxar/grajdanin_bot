<script setup>

</script>
<template>

    <div class="btn-group-vertical w-100" role="group" aria-label="Вертикальное меню действий">
        <button type="button"
                @click="goTo('MunicipalityPage')"
                class="btn btn-outline-primary p-3">Список муниципалитетов</button>
        <button type="button"
                @click="goTo('HelpsPage')"
                class="btn btn-outline-primary p-3">Список видов помощи</button>
        <button type="button"
                @click="goTo('IssuesPage')"
                class="btn btn-outline-primary p-3">Список ситуаций</button>
        <button type="button"
                @click="goTo('UserPage')"
                class="btn btn-outline-primary p-3">Список пользователей
        </button>
        <button type="button"
                @click="goTo('ReportPage')"
                class="btn btn-outline-primary p-3">Список заявок</button>
    </div>



</template>
<script>
export default {
        methods:{
            goTo(name) {
                this.$router.push({ name: name})

            },

        }
}
</script>
