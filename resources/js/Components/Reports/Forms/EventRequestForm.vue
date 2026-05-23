
<script setup>
import AudioRecorder from "@/Components/Reports/Modules/AudioRecorder.vue";
import FileUploader from "@/Components/Reports/Modules/FileUploader.vue";
</script>

<template>
    <form @submit.prevent="submitForm" class="card rounded-2">

        <div class="card-body">
            <h5 class="mb-2">Заявка на мероприятие</h5>


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
                    <option v-for="m in municipalityStore.items" :key="m.id" :value="m.id">
                        {{ m.name }}
                    </option>
                </select>
                <label>Муниципалитет</label>
            </div>



            <!-- EVENT DATE -->
            <div class="form-floating mb-2">
                <input type="date" class="form-control" v-model="form.event_date">
                <label>Дата мероприятия</label>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-floating mb-2">
                <textarea class="form-control" style="height: 120px" v-model="form.description"></textarea>
                <label>Описание мероприятия</label>
            </div>

            <!-- TARGET AUDIENCE -->
            <div class="form-floating mb-2">
                <input type="text" class="form-control" v-model="form.target_audience">
                <label>Целевая аудитория</label>
            </div>

            <!-- BUTTON GROUP -->
            <div class="btn-group w-100 mb-2" role="group">

                <!-- MINUS -->
                <button
                    type="button"
                    class="btn btn-outline-primary rounded-start-4"
                    @click="decrease"
                >
                    <i class="fa-solid fa-minus"></i>
                </button>

                <!-- VALUE BUTTON -->
                <button
                    type="button"
                    class="btn btn-primary fw-bold"
                    @click="toggleInput"
                >
                    {{ form.participants_count }}
                </button>

                <!-- PLUS -->
                <button
                    type="button"
                    class="btn btn-outline-primary rounded-end-4"
                    @click="increase"
                >
                    <i class="fa-solid fa-plus"></i>
                </button>

            </div>

            <!-- HIDDEN INPUT -->
            <div v-if="showInput" class="form-floating mb-2">
                <input
                    type="number"
                    class="form-control"
                    v-model.number="form.participants_count"
                    min="0"
                >
                <label>Количество участников</label>
            </div>

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
                <label class="form-label small">Форматы помощи</label>

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
            </template>


            <div class="form-check form-switch mb-2">
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


            <div class="form-check form-switch mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="needVoiceRecord"
                    v-model="needVoiceRecord"
                >
                <label class="form-check-label fw-bold" for="needVoiceRecord">
                    Подать информацию голосом
                </label>
            </div>

            <template v-if="needVoiceRecord">
                <AudioRecorder v-model="form.audio_files"></AudioRecorder>
            </template>


            <button
                type="submit"
                class="btn btn-primary w-100 p-3 rounded-4">Отправить</button>
        </div>

    </form>
</template>

<script>
import {useHelpFormatsStore} from "@/stores/useHelpFormatsStore";
import {useUsersStore} from "@/stores/users";
import {useMunicipalitiesStore} from "@/stores/useMunicipalitiesStore";
import {useIssueCategoriesStore} from "@/stores/useIssueCategoriesStore";
import {useEventRequestsStore} from "@/stores/reports/useEventRequestsStore";
import {useAuthStore} from "@/stores/auth.js";

export default {
    name: "EventRequestForm",

    data() {
        return {
            needVoiceRecord: false,
            needHelp: false,
            attachDocs: false,
            showInput: false,
            helpStore: useHelpFormatsStore(),
            userStore: useAuthStore(),
            municipalityStore: useMunicipalitiesStore(),
            issueStore: useIssueCategoriesStore(),
            eventRequestStore: useEventRequestsStore(),
            form: {
                fio: {
                    last: "",
                    first: "",
                    middle: ""
                },
                phone: "",
                event_date: "",
                description: "",
                target_audience: "",
                participants_count: 0,
                help_formats: [""],
                comment: ""
            },


        };
    },
    computed: {
        user() {
            return this.userStore?.user || null
        },
        hasAvailableHelpFormats() {
            return this.helpStore.items.length > this.form.help_formats.length
        },
    },
    mounted() {


        this.municipalityStore.fetchAll()
        this.issueStore.fetchAll()
        this.helpStore.fetchAll()

        this.loadForm()

        const today = new Date()
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset())

        const formatted = today.toISOString().slice(0, 10)

        this.form.event_date = formatted;
    },
    methods: {
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
        increase() {
            this.form.participants_count++
        },

        decrease() {
            if (this.form.participants_count > 0) {
                this.form.participants_count--
            }
        },

        toggleInput() {
            this.showInput = !this.showInput
        },
        removeHelpFormat(index) {
            this.form.help_formats.splice(index, 1)
        },


        addHelpFormat() {
            if (this.form.help_formats.length < 10) {
                this.form.help_formats.push("");
            }
        },
        availableHelpFormats(index) {
            const selectedIds = this.form.help_formats.filter(
                (id, i) => i !== index
            )

            return this.helpStore.items.filter(
                hf => !selectedIds.includes(hf.id)
            )
        },
        submitForm() {
            this.saveForm()

            this.eventRequestStore.createEventPdfRequest(this.form)

            this.$emit("success")
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
