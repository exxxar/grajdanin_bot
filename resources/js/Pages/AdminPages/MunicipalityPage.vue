<script setup>
import MunicipalityList from "@/Components/Municipality/MunicipalityList.vue";
import MunicipalityForm from "@/Components/Municipality/MunicipalityForm.vue";
</script>
<template>

    <div class="container-fluid p-3">


        <template v-if="tab==='main'">

            <button
                @click="goTo('MenuPage')"
                class="btn btn-light text-secondary mb-3" style="position: sticky; top:80px; z-index: 100;">К меню</button>

            <MunicipalityList v-on:edit="editElement"></MunicipalityList>

            <nav
                class="navbar bg-transparent position-fixed bottom-0 start-0 w-100 p-3">
                <button
                    @click="tab='form'"
                    type="button"
                    class="btn btn-primary w-100 p-3">
                    Добавить
                </button>
            </nav>
        </template>

        <template v-if="tab==='form'">
            <button
                @click="tab='main'"
                class="btn btn-light text-secondary mb-3" style="position: sticky; top:80px; z-index: 100;">Назад</button>
            <MunicipalityForm v-model="selected" v-on:submit="tab='main'"></MunicipalityForm>
        </template>

    </div>
</template>
<script>
export default {
    data(){
        return {
            selected: null,
            tab:'main'
        }
    },
    methods:{
        editElement(item){
            this.selected = null
            this.$nextTick(()=>{
                this.selected = item
                this.tab = 'form'
            })
        },
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
