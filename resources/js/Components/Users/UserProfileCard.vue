<template>
    <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center gap-2">

            <div class="avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                 style="width: 40px; height: 40px;">
                <i class="fa-solid fa-user"></i>
            </div>

            <div
                @click="goTo('ProfilePage')"
                v-if="user.role>0">
                <div class="fw-bold">{{ user.name ?? 'Имя не указано' }}</div>
                <div class="text-muted" style="font-size:12px;">{{ user.phone }}</div>
                <div class="text-muted" style="font-size:12px;">{{ roles[user.role] }}</div>
            </div>
            <div v-else>
                <p
                    @click="goTo('AuthPage')"
                    class="mb-0" style="line-height: 100%;">Войдите в систему</p>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'UserProfileCard',
    props: {
        user: Object
    },
    data(){
      return {
          roles:[
              "Гость", "Пользователь", "Волонтер", "Официальное лицо", "Администратор", "Суперадмин"
          ]
      }
    },
    methods:{
        goTo(routeName) {
            const el = document.getElementById('sidebar-menu')
            const sidebar = bootstrap.Offcanvas.getOrCreateInstance(el)
            sidebar.hide()

            this.$router.push({ name: routeName })
        },
    }
}
</script>
