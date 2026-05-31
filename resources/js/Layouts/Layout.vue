<script setup>
import Footer from "@/Components/Footer.vue";
import {Head} from '@inertiajs/vue3'
import GlobalAlert from "@/Components/GlobalAlert.vue";
import GlobalConfirmModal from "@/Components/GlobalConfirmModal.vue";
import UserProfileCard from "@/Components/Users/UserProfileCard.vue";
import Loader from "@/components/Loader.vue"
import PrimaryForm from "@/Components/Users/Forms/PrimaryForm.vue";
</script>
<template>

    <Head>
        <title>Гражданин 2.0</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>


    <header
        class="fixed-top-menu"
        data-bs-theme="dark">
        <div class="navbar shadow shadow-sm">
            <div class="container flex-row-reverse p-2">

                    <span
                        data-bs-toggle="modal" data-bs-target="#bot-info-modal"
                        class="text-primary fw-bold cursor-pointer">Гражданин 2.0</span>


                <button class="btn btn-link text-primary fw-bold rounded-0 border-0 p-1 btn-lg" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <Loader :visible="loading" />

    <GlobalAlert></GlobalAlert>
    <GlobalConfirmModal></GlobalConfirmModal>

    <slot/>


    <div class="bottom-nav shadow-lg" v-if="!$route.meta.hide_menu">
        <button
            type="button"
            @click="goTo('MenuPage')"
            v-bind:class="{'active':$route.name === 'MenuPage'}"
            class="nav-item">
            <i class="fa-solid fa-house"></i>
            <span v-if="$route.name === 'MenuPage'">Главная</span>
        </button>

        <button
            type="button"
            v-bind:class="{'active':$route.name === 'ReportsPage'}"
            @click="goTo('ReportsPage')"
            class="nav-item">
            <i class="fa-solid fa-list-check"></i>
            <span v-if="$route.name === 'ReportsPage'">Заявки</span>
        </button>

        <button
            type="button"
            v-bind:class="{'active':$route.name === 'DialogsPage'}"
            @click="goTo('DialogsPage')"
            class="nav-item">
            <i class="fa-regular fa-comments"></i>
            <span v-if="$route.name === 'DialogsPage'">Чат</span>
        </button>

        <button
            type="button"
            v-bind:class="{'active':$route.name === 'HelpPage'}"
            @click="goTo('HelpPage')"
            class="nav-item">
            <i class="fa-regular fa-circle-question"></i>
            <span v-if="$route.name === 'HelpPage'">Справка</span>
        </button>

    </div>

    <template v-if="!$route.meta.hide_footer">
        <Footer></Footer>
    </template>



    <div class="offcanvas offcanvas-start custom-offcanvas"
         style="width: 70%;border-radius: 0px 10px 10px 0px;"
         tabindex="-1" id="sidebar-menu" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title" id="offcanvasExampleLabel">
                <span class="badge bg-primary">Гражданин 2.0</span>
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>


        <div class="offcanvas-body">

            <UserProfileCard
                v-if="user"
                :user="user"></UserProfileCard>
            <!-- Список пунктов меню -->
            <ul class="list-group list-group-flush mt-3">
                <li v-for="item in menuItems"
                    :key="item.route"
                    class="list-group-item p-2">


                    <a href="javascript:void(0)"
                       class="text-decoration-none d-flex align-items-center gap-3 p-3 text-primary"
                       :class="{'fw-bold': $route.name === item.route}"
                       data-bs-dismiss="offcanvas"
                       @click="goTo(item.route)">

                        <i :class="item.icon"></i>
                        <span>{{ item.name }}</span>

                    </a>
                </li>
            </ul>


        </div>
    </div>

    <!-- Модалка редактирования -->
    <div class="modal fade" id="primaryUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Первичная заполнение информации</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!--                    <PrimaryForm
                                            v-if="userStore.self"
                                            v-on:callback="result"
                                            :initial-data="userStore.self"></PrimaryForm>-->
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {useAuthStore} from "@/stores/auth.js";

export default {
    data() {
        return {
            authStore: useAuthStore(),
            currentTheme: '',
            themes: [],
            loading: false,
            menuItems: [
                {
                    name: 'Главная',
                    route: 'MenuPage',
                    icon: 'fa-solid fa-house'
                },

                {
                    name: 'Мои обращения',
                    route: 'ReportsPage',
                    icon: 'fa-solid fa-list-check'
                },
                {
                    name: 'Сообщения',
                    route: 'DialogsPage',
                    icon: 'fa-solid fa-comments'
                },
                {
                    name: 'Настройки',
                    route: 'SettingsPage',
                    icon: 'fa-solid fa-gear'
                }
            ]
        }
    },
    watch: {},
    created() {
        this.authStore.init()

        this.$router.beforeEach((to, from, next) => {
            this.loading = true

            // гарантированная задержка 1 секунда
            setTimeout(() => {
                next()
            }, 500)
        })

        this.$router.afterEach(() => {
            this.loading = false
        })
    },
    computed: {
        user() {
            return this.authStore.user || null
        },
    },

    mounted() {

    },
    methods: {
        goTo(routeName) {
            const el = document.getElementById('sidebar-menu')
            const sidebar = bootstrap.Offcanvas.getOrCreateInstance(el)
            sidebar.hide()

            this.$router.push({name: routeName})
        },
        scrollTop() {
            window.scrollTo(0, 80);
        },


    },


}
</script>

<style>
.fixed-top-menu {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #ffffff;
}
</style>
