<template>
    <div class="container mt-4" style="max-width: 420px;">
        <div class="card shadow-none rounded-2">
            <div class="card-body">
                <h4 class="mb-3 text-center">Вход</h4>

                <form @submit.prevent="submit">

                    <div class="form-floating mb-2">
                        <input
                            type="text"
                            class="form-control"
                            id="phoneInput"
                            placeholder="Телефон"
                            v-model="phone"
                            required
                        >
                        <label for="phoneInput">Телефон</label>
                    </div>

                    <div class="form-floating mb-2">
                        <input
                            type="password"
                            class="form-control"
                            id="passwordInput"
                            placeholder="Пароль"
                            v-model="password"
                            required
                        >
                        <label for="passwordInput">Пароль</label>
                    </div>

                    <button class="btn btn-primary w-100 p-3 rounded-4" type="submit">
                        Войти
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="javascript:void(0)"
                       class="btn btn-link text-primary"
                       @click.prevent="$emit('switch-register')">Регистрация</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth'

export default {
    name: 'LoginForm',

    data() {
        return {
            phone: '',
            password: ''
        }
    },

    methods: {
        async submit() {
            const auth = useAuthStore()
            await auth.login(this.phone, this.password)
            this.$emit('logged-in')
        }
    }
}
</script>
