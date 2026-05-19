<script setup>
import SignUPForm from "@/Components/Auth/SignUPForm.vue";
import SignINForm from "@/Components/Auth/SignINForm.vue";
</script>
<template>
    <template v-if="tab==='login'">
        <SignINForm
            v-on:logged-in="successLogin"
            v-on:switch-register="tab='register'"></SignINForm>
    </template>

    <template v-if="tab==='register'">
        <SignUPForm v-on:switch-login="tab='login'"></SignUPForm>
    </template>

</template>
<script>
export default {
    data(){
        return {
            tab:'login'
        }
    }, methods:{
        successLogin(){
            this.$router.push({ name: 'MenuPage' })
        }
    }
}
</script>
