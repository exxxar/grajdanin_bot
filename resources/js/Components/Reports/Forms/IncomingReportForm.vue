<script setup>
import IssueSelector from "@/Components/Issues/IssueSelector.vue";
import AudioRecorder from "@/Components/Reports/Modules/AudioRecorder.vue";
import FileUploader from "@/Components/Reports/Modules/FileUploader.vue";
import MapPicker from "@/Components/Reports/MapPicker.vue";
</script>
<template>
    <form @submit.prevent="submitForm" class="card rounded-2">
        <div class="card-body">
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
            <template v-if="step===1">
                <h5 class="mb-2">Регистрация жалобы</h5>


                <div class="row g-2">

                    <!-- ФАМИЛИЯ -->
                    <div class="col-12">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control"
                                id="lastName"
                                placeholder="Фамилия"
                                v-model="form.fio.last"
                            >
                            <label for="lastName">Фамилия</label>
                        </div>
                    </div>

                    <!-- ИМЯ -->
                    <div class="col-12">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control"
                                id="firstName"
                                placeholder="Имя"
                                v-model="form.fio.first"
                            >
                            <label for="firstName">Имя</label>
                        </div>
                    </div>

                    <!-- ОТЧЕСТВО -->
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <input
                                type="text"
                                class="form-control"
                                id="middleName"
                                placeholder="Отчество"
                                v-model="form.fio.middle"
                            >
                            <label for="middleName">Отчество</label>
                        </div>
                    </div>

                </div>


                <div class="form-floating mb-2">
                    <input type="text"
                           v-mask="'+7(###) ###-##-##'"
                           class="form-control" v-model="form.phone" required>
                    <label>Номер телефона</label>
                </div>


                <div class="form-floating mb-2">
                    <select class="form-select" v-model="form.municipality_id" required>
                        <template v-for="m in municipalityStore.items" :key="m.id">
                            <option
                                v-if="(m.config?.access_role||0)>=user.role"
                                :key="m.id" :value="m.id">
                                {{ m.name }}
                            </option>
                        </template>
                    </select>
                    <label>Муниципалитет</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="attachDocsSwitch"
                        v-model="needAddressDetails"
                    >
                    <label class="form-check-label fw-bold" for="attachDocsSwitch">
                       Указать точный адрес
                    </label>
                </div>

                <template v-if="needAddressDetails">
                    <div class="row g-2">

                        <!-- ГОРОД -->
                        <div class="col-12">

                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="addrCity"
                                    placeholder="Город"
                                    v-model="form.address.city"
                                >
                                <label for="addrCity">Город</label>
                            </div>
                        </div>

                        <!-- РАЙОН (НЕОБЯЗАТЕЛЬНО) -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="addrDistrict"
                                    placeholder="Район"
                                    v-model="form.address.district"
                                >
                                <label for="addrDistrict">Район (необязательно)</label>
                            </div>
                        </div>

                        <!-- УЛИЦА -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="addrStreet"
                                    placeholder="Улица"
                                    v-model="form.address.street"
                                >
                                <label for="addrStreet">Улица</label>
                            </div>
                        </div>

                        <!-- ДОМ -->
                        <div class="col-12">
                            <div class="form-floating mb-2">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="addrHouse"
                                    placeholder="Дом"
                                    v-model="form.address.house"
                                >
                                <label for="addrHouse">Дом</label>
                            </div>
                        </div>

                    </div>
                    <p
                        @click="needMap=!needMap"
                        class="text-center mb-2 btn btn-link w-100">Или указать на карте
                        <span v-if="needMap">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                        <span v-else>
                            <i class="fa-solid fa-chevron-up"></i>
                        </span>
                    </p>
                    <template v-if="needMap">
                        <MapPicker :address="fullAddress" class="mb-2"></MapPicker>
                    </template>

                </template>
            </template>
            <template v-if="step===2">
                <h5 class="mb-2">Какая у вас проблемная ситуация?</h5>
                <IssueSelector
                    v-model="form.problems"
                    :issues="problems"></IssueSelector>
                <hr>
            </template>
            <template v-if="step===3">
                <h5 class="mb-2">Способы решения проблемы</h5>
                <IssueSelector
                    v-model="form.solutions"
                    :issues="solutions"></IssueSelector>
            </template>
            <template v-if="step===4">
                <h5 class="mb-2">Какие сложности возникли?</h5>
                <IssueSelector
                    v-model="form.difficulties"
                    :issues="difficulties"></IssueSelector>
            </template>
            <template v-if="step===5">
                <!-- REPORT SECTION -->
                <h5 class="mb-2">Дополнительная информация</h5>


                <div class="form-floating mb-2">
                    <textarea class="form-control" style="height: 120px" v-model="form.problem_description" required
                              minlength="10"></textarea>
                    <label>Особенности проблемы *</label>
                </div>

                <div class="form-check form-switch mb-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="needVoiceRecord"
                        v-model="needVoiceRecord"
                    >
                    <label class="form-check-label fw-bold" for="needVoiceRecord">
                        Пояснить проблему голосом
                    </label>
                </div>

                <template v-if="needVoiceRecord">
                    <AudioRecorder v-model="form.audio_files"></AudioRecorder>
                </template>

                <div class="form-check form-switch mb-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="needHelpSwitch"
                        v-model="needHelp"
                    >
                    <label class="form-check-label fw-bold" for="needHelpSwitch">
                        Нужна помощь партии
                    </label>
                </div>

                <template v-if="needHelp">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Форматы помощи партии</label>

                        <template v-for="(item, index) in form.help_formats" :key="index">
                            <div class="input-group flex-nowrap mb-2">
                                <div class="form-floating">
                                    <select
                                        class="form-select"
                                        v-model="form.help_formats[index]"
                                    >
                                        <option
                                            v-for="hf in availableHelpFormats(index)"
                                            :key="hf.id"
                                            :value="hf.id"
                                        >
                                            {{ hf.name }}
                                        </option>
                                    </select>
                                    <label>Формат помощи #{{ index + 1 }}</label>
                                </div>

                                <span class="input-group-text">
            <button
                type="button"
                class="btn btn-outline-danger btn-sm w-100"
                @click="removeHelpFormat(index)"
            >
                ×
            </button>
        </span>
                            </div>
                        </template>


                        <button type="button"
                                class="btn btn-outline-primary w-100 p-2 rounded-4"
                                @click="addHelpFormat"
                                :disabled="!hasAvailableHelpFormats">
                            Добавить формат помощи
                        </button>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" v-model="form.received_at">
                        <label>Дата получения</label>
                    </div>
                </template>


                <div class="form-check form-switch mb-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="attachDocsSwitch"
                        v-model="attachDocs"
                    >
                    <label class="form-check-label fw-bold" for="attachDocsSwitch">
                        Прикрепить документы
                    </label>
                </div>

                <template v-if="attachDocs">
                    <FileUploader v-model="form.documents"></FileUploader>


                </template>


            </template>

            <template v-if="step === 6">
                <div class="card shadow-sm border-light-subtle mb-2">
                    <div class="card-header fw-bold">
                        Сводная информация
                    </div>

                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">

                            <!-- ФИО -->
                            <li class="list-group-item" v-if="summary.received_from">
                                <strong>Ф.И.О.:</strong>
                                <span class="ms-2">{{ summary.received_from }}</span>
                            </li>

                            <!-- Телефон -->
                            <li class="list-group-item" v-if="summary.phone">
                                <strong>Телефон:</strong>
                                <span class="ms-2">{{ summary.phone }}</span>
                            </li>

                            <!-- Муниципалитет -->
                            <li class="list-group-item" v-if="summary.municipality">
                                <strong>Муниципалитет:</strong>
                                <span class="ms-2">{{ summary.municipality }}</span>
                            </li>

                            <!-- Проблемы -->
                            <li class="list-group-item" v-if="summary.problems.length">
                                <strong>Проблемы:</strong>
                                <ul class="mt-1">
                                    <li v-for="item in summary.problems" :key="item.id">
                                        <h6 class="fw-bold small">{{ item.name }}</h6>
                                        <p class="mb-1 small" v-for="p in item.items">{{ p }}</p>
                                    </li>
                                </ul>
                            </li>
                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">Проблемы не указаны</p>
                            </li>

                            <!-- Особенности -->
                            <li class="list-group-item" v-if="summary.problem_description">
                                <strong>Особенности проблемы:</strong>
                                <div class="mt-1 text-muted">{{ summary.problem_description }}</div>
                            </li>

                            <!-- Решения -->
                            <li class="list-group-item" v-if="summary.solutions.length">
                                <strong>Предложенные решения:</strong>
                                <ul class="mt-1">
                                    <li v-for="item in summary.solutions" :key="item.id">
                                        <h6 class="fw-bold small">{{ item.name }}</h6>
                                        <p class="mb-1 small" v-for="p in item.items">{{ p }}</p>
                                    </li>
                                </ul>
                            </li>
                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">Предложений по решению проблемы не поступило</p>
                            </li>

                            <!-- Трудности -->
                            <li class="list-group-item" v-if="summary.difficulties.length">
                                <strong>Трудности:</strong>
                                <ul class="mt-1">
                                    <li v-for="item in summary.difficulties" :key="item.id">
                                        <h6 class="fw-bold small">{{ item.name }}</h6>
                                        <p class="mb-1 small" v-for="p in item.items">{{ p }}</p>
                                    </li>
                                </ul>
                            </li>
                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">Трудностей реализации не обнаружено</p>
                            </li>

                            <!-- Форматы помощи -->
                            <li class="list-group-item" v-if="summary.help_formats.length">
                                <strong>Форматы помощи:</strong>
                                <ul class="mt-1">
                                    <li v-for="item in summary.help_formats" :key="item.id">
                                        <p class="mb-1 small">{{ item.name }}</p>
                                    </li>
                                </ul>
                            </li>

                            <!-- Комментарий -->
                            <li class="list-group-item" v-if="summary.comment">
                                <strong>Комментарий к задаче:</strong>
                                <div class="mt-1 text-muted">{{ summary.comment }}</div>
                            </li>

                        </ul>
                    </div>
                </div>
            </template>

            <nav style="position: sticky; bottom:20px;z-index: 100;">
                <div class="btn-group w-100" role="group" aria-label="Навигация по шагам">
                    <template v-if="step>1&&step<6">
                        <button type="button"
                                @click="goBack"
                                class="btn btn-light p-3 border-light-subtle rounded-start-4">Назад
                        </button>
                        <button type="submit"
                                class="btn btn-primary p-3 rounded-end-4">Вперед
                        </button>
                    </template>
                    <template v-if="step===1">
                        <button type="submit"
                                class="btn btn-primary p-3 rounded-4">Приступить
                        </button>
                    </template>

                    <template v-if="step===6">
                        <button
                            type="submit"
                            class="btn btn-primary p-3 rounded-4"
                            :disabled="submitting">
                            {{ submitting ? 'Отправка...' : 'Отправить' }}
                        </button>
                    </template>

                </div>
            </nav>
        </div>
    </form>
</template>

<script>
import {useMunicipalitiesStore} from "@/stores/useMunicipalitiesStore";
import {useIssueCategoriesStore} from "@/stores/useIssueCategoriesStore";
import {useUsersStore} from "@/stores/users";
import {useHelpFormatsStore} from "@/stores/useHelpFormatsStore";
import {useIncomingReportsStore} from "@/stores/reports/useIncomingReportsStore";
import {useAuthStore} from "@/stores/auth.js";
import {useAlertStore} from "@/stores/utillites/useAlertStore";

export default {
    name: "ReportForm",
    props: ["type"],
    components: {},
    data() {
        return {
            step: 1,
            maxStep: 6,
            submitting: false,
            isRecording: false,
            needHelp: false,
            attachDocs: false,
            needAddressDetails: false,
            needVoiceRecord: false,
            needMap: false,
            mediaRecorder: null,
            audioChunks: [],
            alertStore: useAlertStore(),
            helpStore: useHelpFormatsStore(),
            userStore: useAuthStore(),
            municipalityStore: useMunicipalitiesStore(),
            issueStore: useIssueCategoriesStore(),
            incomingReport: useIncomingReportsStore(),
            selected_problem: null,
            form: {
                type: 0,
                phone: "",
                from_user_id: "",
                to_user_id: "",
                municipality_id: "",
                address: {
                    city: "",
                    district: "",
                    street: "",
                    house: "",
                    coords: {
                        lat: 0,
                        lon: 0,
                    }
                },
                received_at: "",
                documents: [],
                received_from: "",
                fio: {
                    last: "",
                    first: "",
                    middle: ""
                },
                problem_description: "",
                help_formats: [],
                comment: "",
                problems: {},
                solutions: {},
                difficulties: {},
                audio_files: []
            },

            // Эти справочники ты подгрузишь через API
            users: [],
            officials: [],
            helpFormats: []
        };
    },

    computed: {
        summary() {
            const municipality = this.form.municipality_id
                ? this.municipalityStore.byId(this.form.municipality_id)
                : null

            const mapIssues = (obj) => {
                if (!obj) return []
                return Object.entries(obj)
                    .filter(([_, arr]) => arr && arr.length)
                    .map(([id, arr]) => ({
                        id,
                        name: this.issueStore.byId(id)?.name || "Неизвестная категория",
                        items: arr
                    }))
            }

            return {
                received_from: this.form.received_from || null,
                phone: this.form.phone || null,
                municipality: municipality ? municipality.name : null,

                problems: mapIssues(this.form.problems),
                solutions: mapIssues(this.form.solutions),
                difficulties: mapIssues(this.form.difficulties),

                problem_description: this.form.problem_description || null,

                help_formats: (this.form.help_formats || []).map(id => ({
                    id,
                    name: this.helpStore.byId(id)?.name || "Неизвестно"
                })),

                comment: this.form.comment || null
            }
        },
        hasAvailableHelpFormats() {
            return this.helpStore.items.length > this.form.help_formats.length
        },
        user() {
            return this.userStore.user || null
        },
        problems() {
            return this.issueStore.items?.filter(item => item?.type === 0)
        },
        solutions() {
            return this.issueStore.items?.filter(item => item?.type === 1)
        },
        difficulties() {
            return this.issueStore.items?.filter(item => item?.type === 2)
        },
        fullAddress() {
            return [
                this.form.address.city,
                this.form.address.district,
                this.form.address.street,
                this.form.address.house
            ]
                .filter(Boolean)        // убираем пустые поля
                .join(', ')             // собираем в строку
        }
    },

    mounted() {

        window.addEventListener('change-address', this.onAddressChange)

        this.municipalityStore.fetchAll()
        this.issueStore.fetchAll()
        this.helpStore.fetchAll()

        this.loadForm()

        const today = new Date()
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset())

        const formatted = today.toISOString().slice(0, 10)

        this.form.received_at = formatted;


    },

    beforeUnmount() {
        window.removeEventListener('change-address', this.onAddressChange)
    },
    methods: {
        openMapPicker() {
            // Здесь откроешь модалку, карту или мини‑апп
            console.log("Открыть выбор на карте");
        },
        isGuest() {
            return (this.user?.role ?? 0) === 0
        },
        saveForm() {
            localStorage.setItem("report_form_received_from", this.form.received_from)
            localStorage.setItem("report_form_fio_first", this.form.fio.first)
            localStorage.setItem("report_form_fio_last", this.form.fio.last)
            localStorage.setItem("report_form_fio_middle", this.form.fio.middle)
            localStorage.setItem("report_form_received_from", this.form.received_from)
            localStorage.setItem("report_form_phone", this.form.phone)
            localStorage.setItem("report_form_received_municipality_id", this.form.municipality_id)
        },
        loadForm() {

            const receivedFrom = localStorage.getItem("report_form_received_from")
            const phone = localStorage.getItem("report_form_phone")
            const municipalityId = localStorage.getItem("report_form_received_municipality_id")
            const fioFirst = localStorage.getItem("report_form_fio_first")
            const fioLast = localStorage.getItem("report_form_fio_last")
            const fioMiddle = localStorage.getItem("report_form_fio_middle")

            if (receivedFrom !== null) {
                this.form.received_from = receivedFrom
            }

            if (fioFirst !== null) {
                this.form.fio.first = fioFirst
            }

            if (fioLast !== null) {
                this.form.fio.last = fioLast
            }

            if (fioMiddle !== null) {
                this.form.fio.middle = fioMiddle
            }


            if (phone !== null) {
                this.form.phone = phone
            }

            if (municipalityId !== null) {
                this.form.municipality_id = municipalityId
            }
        },
        nextStep() {
            this.saveForm()

            if (this.isGuest() && this.step === 2) {
                this.step = 5
                return
            }
            this.step++
            window.scrollTo(0, 0)

        },

        goBack() {
            if (!this.isGuest()) {
                this.step--
                return
            }

            if (this.step === 5) {
                this.step = 2
                return
            }

            this.step--
        },

        hasIssueSelections(value) {
            if (!value || typeof value !== 'object') {
                return false
            }

            return Object.values(value).some(
                (items) => Array.isArray(items) && items.filter(Boolean).length > 0
            )
        },

        validateStep() {
            switch (this.step) {
                case 1:
                    if (!this.form.received_from?.trim()) {
                        this.alertStore.show('Укажите ФИО заявителя.')
                        return false
                    }
                    if (!this.normalizePhone(this.form.phone)) {
                        this.alertStore.show('Укажите корректный номер телефона.')
                        return false
                    }
                    if (!this.form.municipality_id) {
                        this.alertStore.show('Выберите муниципалитет.')
                        return false
                    }
                    return true
                case 5:
                    if (!this.form.problem_description?.trim() || this.form.problem_description.trim().length < 10) {
                        this.alertStore.show('Опишите проблему не короче 10 символов.')
                        return false
                    }
                    if (!this.form.received_at) {
                        this.alertStore.show('Укажите дату получения обращения.')
                        return false
                    }
                    return true
                default:
                    return true
            }
        },

        normalizePhone(phone) {
            const digits = String(phone || '').replace(/\D/g, '')

            if (digits.length === 11 && digits.startsWith('7')) {
                return '+' + digits
            }

            if (digits.length === 10) {
                return '+7' + digits
            }

            return null
        },

        buildFormData() {
            const fd = new FormData()

            fd.append('type', String(this.form.type ?? 0))
            fd.append('received_from', this.form.received_from.trim())
            fd.append('phone', this.normalizePhone(this.form.phone))
            fd.append('municipality_id', String(this.form.municipality_id))
            fd.append('received_at', this.form.received_at)
            fd.append('problem_description', this.form.problem_description.trim())

            if (this.form.comment?.trim()) {
                fd.append('comment', this.form.comment.trim())
            }

            const helpFormats = this.form.help_formats.filter(Boolean)
            fd.append('help_formats', JSON.stringify(helpFormats))
            fd.append('problems', JSON.stringify(this.form.problems || {}))

            if (!this.isGuest()) {
                fd.append('solutions', JSON.stringify(this.form.solutions || {}))
                fd.append('difficulties', JSON.stringify(this.form.difficulties || {}))
            }

            this.form.documents.forEach((file, index) => {
                fd.append(`documents[${index}]`, file)
            })

            this.form.audio_files.forEach((item, index) => {
                const blob = item.file || item
                const file = blob instanceof Blob
                    ? new File([blob], `audio-${index}.webm`, {type: blob.type || 'audio/webm'})
                    : blob
                fd.append(`audio_files[${index}]`, file)
            })

            return fd
        },

        availableHelpFormats(index) {
            const selectedIds = this.form.help_formats.filter(
                (id, i) => i !== index
            )

            return this.helpStore.items.filter(
                hf => !selectedIds.includes(hf.id)
            )
        },

        removeHelpFormat(index) {
            this.form.help_formats.splice(index, 1)
        },


        addHelpFormat() {
            if (this.form.help_formats.length < 10) {
                this.form.help_formats.push("");
            }
        },
        onAddressChange(event) {
            const data = event.detail

            this.form.address = {
                city: data.city,
                district: data.borough,
                street: data.road,
                house: data.house_number,
                lat: data.lat,
                lng: data.lng,
                full: data.address
            }
        },

        async submitForm() {
            if (!this.validateStep()) {
                return
            }

            if (this.step < 6) {
                this.nextStep()
                return
            }

            if (!this.user) {
                this.alertStore.show('Не удалось определить пользователя. Обновите страницу.')
                return
            }

            this.submitting = true

            try {
                const formData = this.buildFormData()
                const result = await this.incomingReport.createFromForm(formData)

                this.alertStore.show('Заявка успешно отправлена', 'success')
                this.$emit('success', result)

                if (result.report_id) {
                    this.$router.push({
                        name: 'ChatPage',
                        query: {report: result.report_id},
                    })
                }
            } finally {
                this.submitting = false
            }
        }
    }
};
</script>

<style scoped>
form {
    max-width: 480px;
    margin: 0 auto;
}


</style>
