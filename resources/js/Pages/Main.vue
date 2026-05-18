<script setup>
import Layout from "@/Layouts/Layout.vue";

</script>
<template>

    <Layout v-if="!authStore.user?.blocked_at">

        <template #default>

            <router-view/>

            <div class="bottom-nav shadow-lg">
                <button class="nav-item active">
                    <i class="fa-solid fa-house"></i>
                    <span>Главная</span>
                </button>

                <button class="nav-item">
                    <i class="fa-solid fa-list-check"></i>
                </button>

                <button class="nav-item">
                    <i class="fa-solid fa-plus"></i>
                </button>

                <button class="nav-item">
                    <i class="fa-solid fa-user"></i>
                </button>

            </div>

        </template>


    </Layout>

    <div class="container py-3" v-else>
        <p class="alert alert-light text-center" >
            Доступ ограничен
        </p>
    </div>

    <div class="modal fade" id="installPwaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Установить приложение</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Вы можете установить Kanban как приложение и запускать его прямо с рабочего стола.</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Позже</button>
                    <button class="btn btn-primary" @click="installPWA">Установить</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import { useAuthStore } from '@/stores/auth.js'

export default {
    data() {
        return {
            authStore: useAuthStore()
        }
    },
    created() {

    },
    computed: {

    },
    mounted() {
        this.initPush()


    },
    methods: {
        installPWA() {
            window.installPWA()
        },
        async initPush() {

            /*  const oldRegistration = await navigator.serviceWorker.ready
              const oldSubscription =  await oldRegistration.pushManager.getSubscription()
              if (oldSubscription) {
                  oldSubscription.unsubscribe()
                  console.log('Старая подписка удалена')
              }
  */
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                console.warn('Push notifications not supported')
                return
            }

            const registration = await navigator.serviceWorker.register('/sw.js')

            const permission = await Notification.requestPermission()
            if (permission !== 'granted') {
                console.warn('User denied notifications')
                return
            }

      /*      const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.vapidPublicKey,

            })
*/
          /*  await axios.post('/api/push/subscribe', {
                subscription,
                board_uuid: this.board.uuid
            })*/
        }
    }

}
</script>

<style>
.bottom-nav {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);

    width: calc(100% - 30px);
    max-width: 500px;

    background: #fff;
    border-radius: 24px;

    padding: 11px 11px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    z-index: 1000;
}

.nav-item {
    border: none;
    background: transparent;

    width: 56px;
    height: 56px;

    border-radius: 18px;

    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    gap: 10px;

    color: #ffffff;

    transition: .2s ease;
}

.nav-item i {
    font-size: 18px;
}

.nav-item span {
    font-size: 14px;
    font-weight: bold;
}

.nav-item.active {
    width: auto;
    padding: 0 20px;

    background: #47c2c0;
}

.nav-item:not(.active) {
    color: #47c2c0;
}

.nav-item:hover {
    transform: translateY(-2px);
}
</style>
