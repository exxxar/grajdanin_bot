<template>
    <div class="container mt-4" style="max-width: 520px;">
        <div class="card shadow-none rounded-2">
            <div class="card-body">

                <h4 class="mb-3 text-center">Профиль</h4>

                <!-- Кнопки управления -->
                <div class="text-end mb-3">
                    <button
                        v-if="!editMode"
                        class="btn btn-outline-primary w-100 p-2"
                        @click="enableEdit"
                    >
                        <i class="fa-solid fa-pen"></i> Редактировать
                    </button>

                    <div v-else class="d-flex gap-2 justify-content-end">
                        <button class="btn btn-success btn-sm" @click="save">
                            <i class="fa-solid fa-check"></i> Сохранить
                        </button>
                        <button class="btn btn-secondary btn-sm" @click="cancel">
                            <i class="fa-solid fa-xmark"></i> Отмена
                        </button>
                    </div>
                </div>

                <!-- ========================= -->
                <!-- РЕЖИМ ПРОСМОТРА -->
                <!-- ========================= -->
                <div v-if="!editMode">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Фамилия</span>
                            <span>{{ form.last_name }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Имя</span>
                            <span>{{ form.first_name }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Отчество</span>
                            <span>{{ form.middle_name || '—' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Телефон</span>
                            <span>{{ form.phone }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Дата рождения</span>
                            <span>{{ form.birth_date }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Округ</span>
                            <span>{{ municipalityName }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Город</span>
                            <span>{{ form.city }}</span>
                        </li>

                    </ul>
                </div>

                <!-- ========================= -->
                <!-- РЕЖИМ РЕДАКТИРОВАНИЯ -->
                <!-- ========================= -->
                <form v-else @submit.prevent="save">

                    <!-- ФИО -->
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastName"
                                       placeholder="Фамилия" v-model="form.last_name">
                                <label for="lastName">Фамилия</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstName"
                                       placeholder="Имя" v-model="form.first_name">
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
                               placeholder="Телефон" v-model="form.phone">
                        <label for="phone">Телефон</label>
                    </div>

                    <!-- Дата рождения -->
                    <div class="form-floating mt-2">
                        <input type="date" class="form-control" id="birthDate"
                               placeholder="Дата рождения" v-model="form.birth_date">
                        <label for="birthDate">Дата рождения</label>
                    </div>

                    <!-- Округ -->
                    <div class="form-floating mt-2">
                        <select class="form-select" id="district"
                                v-model="form.municipality_id">
                            <option value="" disabled>Выберите округ</option>
                            <option
                                v-for="m in municipalityStore.items"
                                :key="m.id"
                                :value="m.id"
                            >
                                {{ m.name }}
                            </option>
                        </select>
                        <label for="district">Административный округ</label>
                    </div>

                    <!-- Город -->
                    <div class="form-floating mt-2">
                        <input type="text" class="form-control" id="city"
                               placeholder="Город" v-model="form.city">
                        <label for="city">Город проживания</label>
                    </div>

                    <!-- ========================= -->
                    <!-- БЛОК СМЕНЫ ПАРОЛЯ -->
                    <!-- ========================= -->
                    <h6 class="mt-4 mb-2">Смена пароля</h6>

                    <div class="form-floating mt-2">
                        <PasswordToggle
                            id="password"
                            label="Новый пароль"
                            placeholder="Новый пароль"
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

                </form>

            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth.js'
import { useMunicipalitiesStore } from "@/stores/useMunicipalitiesStore.ts"
import PasswordToggle from "@/Components/Auth/PasswordToggle.vue"

export default {
    name: 'ProfileForm',

    components: { PasswordToggle },

    data() {
        return {
            editMode: false,

            auth: useAuthStore(),
            municipalityStore: useMunicipalitiesStore(),

            form: {
                last_name: '',
                first_name: '',
                middle_name: '',
                phone: '',
                birth_date: '',
                municipality_id: '',
                city: '',
                password: '',
                password_confirm: ''
            },

            original: {}
        }
    },

    computed: {
        municipalityName() {
            const m = this.municipalityStore.items.find(
                x => x.id === this.form.municipality_id
            )
            return m ? m.name : '—'
        }
    },

    async mounted() {
        await this.municipalityStore.fetchAll()
        this.loadUser()
    },

    methods: {
        loadUser() {
            const u = this.auth.user

            this.form = {
                last_name: u.last_name,
                first_name: u.first_name,
                middle_name: u.middle_name,
                phone: u.phone,
                birth_date: u.birth_date,
                municipality_id: u.municipality_id,
                city: u.city,
                password: '',
                password_confirm: ''
            }

            this.original = JSON.parse(JSON.stringify(this.form))
        },

        enableEdit() {
            this.editMode = true
        },

        cancel() {
            this.form = JSON.parse(JSON.stringify(this.original))
            this.editMode = false
        },

        async save() {
            if (this.form.password && this.form.password !== this.form.password_confirm) {
                alert('Пароли не совпадают')
                return
            }

            await this.auth.updateProfile(this.form)
            this.loadUser()
            this.editMode = false
        }
    }
}
</script>
