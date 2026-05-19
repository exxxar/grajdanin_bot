<template>
    <footer
        style="min-height:300px;"
        class="footer bg-light border-top mt-4 pt-4 pb-5 position-relative">

        <!-- Контент футера -->
        <div class="container text-center">

            <!-- Описание -->
            <p class="text-muted small mb-3">
                Платформа для обращений граждан и взаимодействия с администрацией.
                Работает в тестовом режиме.
            </p>

            <!-- Контакты -->
            <div class="d-flex justify-content-center gap-4 fs-4 mb-3">

                <a href="mailto:support@example.com" class="text-primary">
                    <i class="fa-solid fa-envelope"></i>
                </a>

                <a href="tel:+70000000000" class="text-primary">
                    <i class="fa-solid fa-phone"></i>
                </a>

                <a href="https://t.me/example" target="_blank" class="text-primary">
                    <i class="fa-brands fa-telegram"></i>
                </a>

                <a href="https://vk.com/example" target="_blank" class="text-primary">
                    <i class="fa-brands fa-vk"></i>
                </a>

            </div>

            <!-- Копирайт -->
            <div class="text-muted small">
                © {{ new Date().getFullYear() }} Все права защищены
            </div>
        </div>

        <!-- Кнопка "наверх" -->
        <button
            class="btn btn-primary scroll-top-btn"
            v-show="showScroll"
            @click="scrollTop"
        >
            <i class="fa-solid fa-arrow-up"></i>
        </button>

    </footer>
</template>

<script>
export default {
    name: 'AppFooter',

    data() {
        return {
            showScroll: false
        }
    },

    mounted() {
        window.addEventListener('scroll', this.checkScroll)
    },

    beforeUnmount() {
        window.removeEventListener('scroll', this.checkScroll)
    },

    methods: {
        checkScroll() {
            this.showScroll = window.scrollY > 200
        },

        scrollTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            })
        }
    }
}
</script>

<style scoped>
.scroll-top-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}
</style>
