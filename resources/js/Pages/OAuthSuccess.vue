<template>
    <div class="text-center mt-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-3">Вход выполняется...</p>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth'

export default {
    async mounted() {
        const token = new URLSearchParams(window.location.search).get('token')

        if (token) {
            const auth = useAuthStore()
            await auth.loginWithToken(token)
            this.$router.push('/profile')
        }
    }
}
</script>
