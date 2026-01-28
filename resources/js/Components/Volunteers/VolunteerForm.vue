<template>
    <form @submit="handleSubmit" class="p-3 border rounded">

        <!-- Прогресс -->
        <div class="my-3">
            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    :style="{ width: (step / maxStep * 100) + '%' }"
                ></div>
            </div>
            <p class="text-center mt-2">Шаг {{ step }} из {{ maxStep }}</p>
        </div>

        <!-- ====================== ШАГ 1 ====================== -->
        <template v-if="step === 1">
            <h5 class="mb-3">Личные данные</h5>

            <div class="form-floating mb-2">
                <input v-model="form.lastName" class="form-control" id="lastName" placeholder="Фамилия">
                <label for="lastName">Фамилия</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.lastName }}</div>

            <div class="form-floating mb-2">
                <input v-model="form.firstName" class="form-control" id="firstName" placeholder="Имя">
                <label for="firstName">Имя</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.firstName }}</div>

            <div class="form-floating mb-2">
                <input v-model="form.middleName" class="form-control" id="middleName" placeholder="Отчество">
                <label for="middleName">Отчество</label>
            </div>

            <div class="form-floating mb-2">
                <input type="date" v-model="form.birthDate" class="form-control" id="birthDate"
                       placeholder="Дата рождения">
                <label for="birthDate">Дата рождения</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.birthDate }}</div>

            <div class="form-floating mb-2">
                <select v-model="form.gender" class="form-select" id="gender">
                    <option value="">Не выбрано</option>
                    <option value="1">Мужской</option>
                    <option value="0">Женский</option>
                </select>
                <label for="gender">Пол</label>
            </div>

            <div class="form-floating mb-2">
                <input v-model="form.citizenship" class="form-control" id="citizenship" placeholder="Гражданство">
                <label for="citizenship">Гражданство</label>
            </div>
        </template>


        <!-- ====================== ШАГ 2 ====================== -->
        <template v-if="step === 2">
            <h5 class="mb-3">Контакты</h5>

            <div class="form-floating mb-2">
                <input v-model="form.phone" class="form-control" id="phone" placeholder="Телефон">
                <label for="phone">Телефон</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.phone }}</div>

            <div class="form-floating mb-2">
                <input v-model="form.email" class="form-control" id="email" placeholder="Email">
                <label for="email">Email</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.email }}</div>

            <div class="form-floating mb-2">
                <input v-model="form.city" class="form-control" id="city" placeholder="Город">
                <label for="city">Город</label>
            </div>
            <div class="text-danger small mb-2">{{ errors.city }}</div>

            <div class="form-floating mb-2">
                <input v-model="form.address" class="form-control" id="address" placeholder="Адрес">
                <label for="address">Адрес (необязательно)</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.preferredContact" class="form-select" id="preferredContact">
                    <option value="">Не выбрано</option>
                    <option>Телефон</option>
                    <option>Мессенджер</option>
                    <option>Email</option>
                </select>
                <label for="preferredContact">Предпочтительный способ связи</label>
            </div>
        </template>


        <!-- ====================== ШАГ 3 ====================== -->
        <template v-if="step === 3">
            <h5 class="mb-3">Мессенджеры и соцсети</h5>

            <div class="form-floating mb-2">
                <input v-model="form.telegram" class="form-control" id="telegram" placeholder="@username">
                <label for="telegram">Telegram</label>
            </div>

            <div class="form-floating mb-2">
                <input v-model="form.whatsapp" class="form-control" id="whatsapp" placeholder="WhatsApp">
                <label for="whatsapp">WhatsApp</label>
            </div>

            <div class="form-floating mb-2">
                <input v-model="form.vk" class="form-control" id="vk" placeholder="VK / Facebook / Instagram">
                <label for="vk">VK / Facebook / Instagram</label>
            </div>

            <div class="form-floating mb-2">
                <input v-model="form.website" class="form-control" id="website" placeholder="Сайт">
                <label for="website">Личный сайт</label>
            </div>
        </template>

        <!-- ====================== ШАГ 4 ====================== -->
        <template v-if="step === 4">
            <h5 class="mb-3">Профессиональная информация</h5>

            <div class="form-floating mb-2">
                <input v-model="form.profession" class="form-control" id="profession" placeholder="Профессия">
                <label for="profession">Профессия</label>
            </div>

            <div class="mb-2">
                <label class="form-label d-block">Навыки</label>

                <div
                    class="form-check mb-1"
                    v-for="(skill, index) in skillsList"
                    :key="index"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :id="'skill' + index"
                        :value="skill"
                        v-model="form.skills"
                    >
                    <label class="form-check-label" :for="'skill' + index">
                        {{ skill }}
                    </label>
                </div>
            </div>


            <div class="form-floating mb-2">
                <input v-model="form.languages" class="form-control" id="languages" placeholder="Языки">
                <label for="languages">Иностранные языки</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.hasExperience" class="form-select" id="hasExperience">
                    <option value="">Не выбрано</option>
                    <option :value="true">Да</option>
                    <option :value="false">Нет</option>
                </select>
                <label for="hasExperience">Опыт волонтёрства</label>
            </div>

            <template v-if="form.hasExperience === true">
                <div class="form-floating mb-2" v-if="form.hasExperience">
                    <input v-model="form.experience" class="form-control" id="languages" placeholder="Языки">
                    <label for="languages">Сколько лет опыта?</label>
                </div>
            </template>


            <div class="form-floating mb-2">
                <textarea v-model="form.experienceDetails" class="form-control" id="experienceDetails"
                          placeholder="Опыт" style="height: 100px"></textarea>
                <label for="experienceDetails">Какие задачи выполняли</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.readyToLearn" class="form-select" id="readyToLearn">
                    <option value="">Не выбрано</option>
                    <option>Да</option>
                    <option>Нет</option>
                </select>
                <label for="readyToLearn">Готовность к обучению</label>
            </div>
        </template>


        <!-- ====================== ШАГ 5 ====================== -->
        <template v-if="step === 5">
            <h5 class="mb-3">Готовность к участию</h5>

            <div class="form-floating mb-2">
                <input v-model="form.timePerWeek" class="form-control" id="timePerWeek" placeholder="Часы">
                <label for="timePerWeek">Часы в неделю</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.participationFormat" class="form-select" id="participationFormat">
                    <option>Онлайн</option>
                    <option>Офлайн</option>
                    <option>Смешанный</option>
                </select>
                <label for="participationFormat">Формат участия</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.canTravel" class="form-select" id="canTravel">
                    <option>Да</option>
                    <option>Нет</option>
                </select>
                <label for="canTravel">Возможность выезжать</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.weekendWork" class="form-select" id="weekendWork">
                    <option>Да</option>
                    <option>Нет</option>
                </select>
                <label for="weekendWork">Работа в выходные</label>
            </div>

            <div class="mb-2">
                <label class="form-label d-block">Направления деятельности</label>

                <div
                    class="form-check mb-1"
                    v-for="(dir, index) in directionsList"
                    :key="index"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :id="'direction' + index"
                        :value="dir"
                        v-model="form.directions"
                    >
                    <label class="form-check-label" :for="'direction' + index">
                        {{ dir }}
                    </label>
                </div>
            </div>


            <div class="form-floating mb-2">
                <input v-model="form.otherDirection" class="form-control" id="otherDirection" placeholder="Другое">
                <label for="otherDirection">Другое направление</label>
            </div>

            <div class="form-floating mb-2">
                <select v-model="form.clothingSize" class="form-select" id="clothingSize">
                    <option
                        v-for="(size, index) in clothingSizes"
                        :key="index"
                        :value="size"
                    >
                        {{ size }}
                    </option>
                </select>
                <label for="clothingSize">Размер одежды (мерч)</label>
            </div>


            <div class="form-check mb-2">
                <input type="checkbox" v-model="form.sendTasks" class="form-check-input" id="sendTasks">
                <label class="form-check-label" for="sendTasks">Присылать задания волонтёра</label>
            </div>
        </template>


        <!-- ====================== ШАГ 6 ====================== -->
        <template v-if="step === 6">
            <h5 class="mb-3">Дополнительные сведения</h5>

            <div class="form-floating mb-2">
                <textarea v-model="form.motivation" class="form-control" id="motivation" placeholder="Причина"
                          style="height: 100px"></textarea>
                <label for="motivation">Почему хотите стать волонтёром</label>
            </div>

            <div class="form-floating mb-2">
                <textarea v-model="form.health" class="form-control" id="health" placeholder="Ограничения"
                          style="height: 100px"></textarea>
                <label for="health">Ограничения по здоровью</label>
            </div>

            <h6 class="opacity-75 mt-3">Ограничения по здоровью</h6>

            <!-- Выбор: есть / нет -->
            <div class="list-group my-3">

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3"
                   :class="{ active: form.healthLimit === 'none' }"
                   @click="form.healthLimit = 'none'; form.healthDetails = []">
                    <i class="fa-regular fa-heart mr-2"></i>
                    <span class="px-2">Нет ограничений по здоровью</span>
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3"
                   :class="{ active: form.healthLimit === 'has' }"
                   @click="form.healthLimit = 'has'">
                    <i class="fa-solid fa-house-medical-flag mr-2"></i>
                    <span class="px-2">Есть ограничения по здоровью</span>
                </a>

            </div>

            <!-- Если есть ограничения — показываем чекбоксы -->
            <div class="list-group my-3" v-if="form.healthLimit === 'has'">

                <a v-for="opt in healthOptions"
                   :key="opt.id"
                   href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between">

                    <label :for="'health-' + opt.id">
                        <i :class="opt.icon + ' mr-2'"></i>
                        {{ opt.label }}
                    </label>

                    <input type="checkbox"
                           class="form-check-input"
                           :id="'health-' + opt.id"
                           :value="opt.value"
                           v-model="form.healthDetails">
                </a>

            </div>


            <div class="form-floating mb-2">
                <textarea v-model="form.comment" class="form-control" id="comment" placeholder="Комментарий"
                          style="height: 100px"></textarea>
                <label for="comment">Комментарий</label>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" v-model="form.agreePersonal" class="form-check-input" id="agreePersonal">
                <label class="form-check-label" for="agreePersonal">Согласие на обработку персональных данных</label>
                <div class="text-danger small">{{ errors.agreePersonal }}</div>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" v-model="form.agreeNotifications" class="form-check-input"
                       id="agreeNotifications">
                <label class="form-check-label" for="agreeNotifications">Согласие на уведомления</label>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" v-model="form.agreeEvents" class="form-check-input" id="agreeEvents">
                <label class="form-check-label" for="agreeEvents">Согласие на участие в мероприятиях</label>
            </div>
        </template>


        <nav style="position: sticky; bottom:20px;z-index: 100;">
            <div class="btn-group w-100" role="group" aria-label="Basic example">
                <template v-if="step>1&&step<5">
                    <button type="button"
                            @click="step--"
                            class="btn btn-light p-3">Назад
                    </button>
                    <button type="submit"
                            class="btn btn-primary p-3">Вперед
                    </button>
                </template>
                <template v-if="step===1">
                    <button type="submit"
                            class="btn btn-primary p-3">Вперед
                    </button>
                </template>

                <template v-if="step===5">
                    <button
                        type="submit" class="btn btn-primary p-3">Отправить
                    </button>
                </template>

            </div>
        </nav>


    </form>
</template>

<script>
export default {
    name: "VolunteerForm",

    data() {
        return {
            step: 1,
            maxStep: 6,
            skillsList: [
                "Работа с людьми",
                "Физическая работа",
                "Экспертные знания в определенной сфере",
                "Организация мероприятий",
                "Дизайн",
                "SMM",
                "IT",
                "Фото/видео",
                "Юридическая помощь",
                "Аналитика"
            ],
            directionsList: [
                "Мероприятия",
                "Информационная поддержка",
                "Аналитика",
                "Физическая работа",
                "Работа с людьми",
                "Логистика",
                "IT",
                "Профессиональная помощь",
                "Юридическая помощь"
            ],

            clothingSizes: ["XS", "S", "M", "L", "XL", "XXL"],
            healthOptions: [
                {
                    id: 1, icon: "fa-solid fa-head-side-mask", label: "Часто болею", value: "болею"
                },
                {
                    id: 2,
                    icon: "fa-solid fa-ear-deaf",
                    label: "Плохо слышит / говорит",
                    value: "плохо слышит или говорит"
                },
                {
                    id: 3, icon: "fa-solid fa-glasses", label: "Слабовидящий", value: "слабовидящий"
                },
                {
                    id: 4,
                    icon: "fa-solid fa-wheelchair",
                    label: "Ограничения мобильности",
                    value: "ограничения мобильности"
                },
                {
                    id: 5,
                    icon: "fa-solid fa-person-dots-from-line",
                    label: "Разновидности аллергии",
                    value: "аллергия"
                }
            ],
            form: {
                lastName: "",
                firstName: "",
                middleName: "",
                birthDate: "",
                gender: "",
                citizenship: "",

                phone: "",
                email: "",
                city: "",
                address: "",
                preferredContact: "",

                telegram: "",
                whatsapp: "",
                vk: "",
                website: "",

                profession: "",
                skills: [],
                languages: "",
                hasExperience: null,
                experience: 1,
                experienceDetails: "",
                readyToLearn: "",

                timePerWeek: "",
                participationFormat: "",
                canTravel: "",
                weekendWork: "",
                directions: [],
                otherDirection: "",
                clothingSize: "",
                sendTasks: false,

                motivation: "",
                health: "",
                comment: "",
                agreePersonal: false,
                agreeNotifications: false,
                agreeEvents: false,

                healthLimit: null, // "none" | "has"
                healthDetails: []
            },

            errors: {},
        };
    },

    methods: {
        validateStep() {
            this.errors = {};

            if (this.step === 1) {
                if (!this.form.lastName) this.errors.lastName = "Введите фамилию";
                if (!this.form.firstName) this.errors.firstName = "Введите имя";
                if (!this.form.birthDate) this.errors.birthDate = "Укажите дату рождения";
            }

            if (this.step === 2) {
                if (!this.form.phone) this.errors.phone = "Введите телефон";
                if (!this.form.email) this.errors.email = "Введите email";
                if (!this.form.city) this.errors.city = "Укажите город";
            }

            if (this.step === 6) {
                if (!this.form.agreePersonal)
                    this.errors.agreePersonal = "Необходимо согласие";
            }

            return Object.keys(this.errors).length === 0;
        },

        handleSubmit(e) {
            e.preventDefault();

            if (!this.validateStep()) return;

            if (this.step < this.maxStep) {
                this.step++;
                return;
            }

            // Финальная отправка
            console.log("SUBMIT DATA:", JSON.parse(JSON.stringify(this.form)));
            alert("Анкета отправлена!");
        },
    },
};
</script>
