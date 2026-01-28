<script setup>
import MenuAdmin from "@/Components/MenuAdmin.vue";
import MenuUser from "@/Components/MenuUser.vue";
import MenuSuperAdmin from "@/Components/MenuSuperAdmin.vue";
import RoleSwitcher from "@/Components/Users/RoleSwitcher.vue";

import { useI18n } from "vue-i18n";
import MenuVolunteer from "@/Components/MenuVolunteer.vue";
import MenuOfficial from "@/Components/MenuOfficial.vue";
const { t } = useI18n();
</script>

<template>


    <div class="container-fluid p-3" v-if="user">
        <template v-if="user.role === 0">
            <MenuUser></MenuUser>
        </template>

        <MenuVolunteer v-if="user.role === 1"></MenuVolunteer>
        <MenuOfficial v-if="user.role === 2"></MenuOfficial>
        <MenuAdmin v-if="user.role === 3"></MenuAdmin>

        <template v-if="user.role === 4">
            <MenuSuperAdmin></MenuSuperAdmin>
        </template>

        <RoleSwitcher v-if="user.base_role===4"></RoleSwitcher>
    </div>

</template>
<script>
import {useUsersStore} from "@/stores/users";

export default {
    data() {
        return {
            userStore: useUsersStore()
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        user() {
            return this.userStore.self || null
        },
    },
    methods: {}

}
</script>
