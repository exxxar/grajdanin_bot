<script setup>
import MenuAdmin from "@/Components/MenuAdmin.vue";
import MenuUser from "@/Components/MenuUser.vue";
import MenuSuperAdmin from "@/Components/MenuSuperAdmin.vue";
import RoleSwitcher from "@/Components/Users/RoleSwitcher.vue";

import {useI18n} from "vue-i18n";
import MenuVolunteer from "@/Components/MenuVolunteer.vue";
import MenuOfficial from "@/Components/MenuOfficial.vue";

const {t} = useI18n();
</script>

<template>


    <div class="container-fluid p-2" style="min-height:500px;" v-if="user">
        <MenuUser></MenuUser>

        <template v-if="user.role === 2">
            <MenuVolunteer></MenuVolunteer>
        </template>

        <template v-if="user.role === 3">
            <MenuOfficial></MenuOfficial>
        </template>

        <template v-if="[4,5].includes(user.role)">
            <MenuAdmin v-if="user.role === 3"></MenuAdmin>
        </template>

        <template v-if="user.role === 5">
            <MenuSuperAdmin></MenuSuperAdmin>
        </template>

        <RoleSwitcher v-if="user.base_role===5"></RoleSwitcher>
    </div>

</template>
<script>
import {useAuthStore} from "@/stores/auth.js";

export default {
    data() {
        return {
            authStore: useAuthStore()
        }
    },
    computed: {
        user() {
            return this.authStore.user || null
        },
    },
    methods: {}

}
</script>
