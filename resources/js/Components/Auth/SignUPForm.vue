<script setup>
import PasswordToggle from "@/Components/Auth/PasswordToggle.vue";
</script>
<template>
    <div class="container mt-4" style="max-width: 520px;">
        <div class="card shadow-none rounded-2">
            <div class="card-body">
                <h4 class="mb-3 text-center">Регистрация</h4>

                <form @submit.prevent="submit">

                    <!-- ФИО -->
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastName"
                                       placeholder="Фамилия" v-model="form.last_name" required>
                                <label for="lastName">Фамилия</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstName"
                                       placeholder="Имя" v-model="form.first_name" required>
                                <label for="firstName">Имя</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="middleName"
                                       placeholder="Отчество" v-model="form.middle_name">
                                <label for="middleName">Отчество</label>
                            </div>
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="form-floating mt-2">
                        <input type="text"
                               v-mask="'+7(###) ###-##-##'"
                               class="form-control" id="phone"
                               placeholder="Телефон" v-model="form.phone" required>
                        <label for="phone">Телефон</label>
                    </div>

                    <!-- Дата рождения -->
                    <div class="form-floating mt-2">
                        <input type="date" class="form-control" id="birthDate"
                               placeholder="Дата рождения" v-model="form.birth_date" required>
                        <label for="birthDate">Дата рождения</label>
                    </div>

                    <!-- Округ -->
                    <div class="form-floating mt-2">
                        <select class="form-select" id="district"
                                v-model="form.municipality_id" required>
                            <option value="" disabled>Выберите округ</option>

                                <option
                                    v-for="m in municipalityStore.items"
                                    :key="m.id" :value="m.id">
                                    {{ m.name }}
                                </option>

                        </select>
                        <label for="district">Административный округ</label>
                    </div>

                    <!-- Город -->
                    <div class="form-floating mt-2">
                        <input type="text" class="form-control" id="city"
                               placeholder="Город" v-model="form.city" required>
                        <label for="city">Город проживания</label>
                    </div>

                    <div class="form-floating mt-2">
                        <PasswordToggle
                            id="password"
                            label="Пароль"
                            placeholder="Пароль"
                            v-model="form.password"
                        />
                    </div>

                    <div class="form-floating mt-2">
                        <PasswordToggle
                            id="passwordConfirm"
                            label="Подтверждение пароля"
                            placeholder="Подтверждение пароля"
                            v-model="form.password_confirm"
                        />
                    </div>


                    <!-- Согласие -->
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="agree"
                               v-model="form.agree" required>
                        <label class="form-check-label" for="agree">
                            Согласие на обработку персональных данных (ФЗ‑152)
                        </label>
                    </div>

                    <button
                        :disabled="!form.agree"
                        class="btn btn-primary rounded-4 w-100 mt-2 p-3" type="submit">
                        Завершить регистрацию
                    </button>

                </form>

                <div class="text-center mt-2">
                    <a href="javascript:void(0)"
                       class="btn btn-link text-primary"
                       @click.prevent="$emit('switch-login')">Уже есть аккаунт</a>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { useAuthStore } from '@/stores/auth.js'
import {useMunicipalitiesStore} from "@/stores/useMunicipalitiesStore.ts";

export default {
    name: 'RegisterForm',

    data() {
        return {

            municipalityStore: useMunicipalitiesStore(),

            showPassword: false,
            showConfirm: false,

            form: {
                last_name: '',
                first_name: '',
                middle_name: '',
                phone: '',
                birth_date: '',
                municipality_id: '',
                city: '',
                password: '',
                password_confirm: '',
                agree: false
            },


        }
    },

    async mounted() {

        this.municipalityStore.fetchAll()

    },

    methods: {
        async submit() {
            if (this.form.password !== this.form.password_confirm) {
                alert('Пароли не совпадают')
                return
            }

            const auth = useAuthStore()
            await auth.upgrade(this.form)
            this.$emit('registered')
        }
    }
}
</script>
