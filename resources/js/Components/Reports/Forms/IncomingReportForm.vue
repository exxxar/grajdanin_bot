<script setup>
import IssueSelector from "@/Components/Issues/IssueSelector.vue";
import AudioRecorder from "@/Components/Reports/Modules/AudioRecorder.vue";
import FileUploader from "@/Components/Reports/Modules/FileUploader.vue";
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
                <p class="text-center mt-2">Шаг {{ step }} из {{ maxStep  }}</p>
            </div>
            <template v-if="step===1">
                <h5 class="mb-2">Регистрация жалобы</h5>


                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="form.received_from" required>
                    <label>От кого (ФИО)</label>
                </div>

                <div class="form-floating mb-2">
                    <input type="text"
                           v-mask="'+7(###) ###-##-##'"
                           class="form-control" v-model="form.phone" required>
                    <label>Номер телефона</label>
                </div>


                <div class="form-floating mb-2">
                    <select class="form-select" v-model="form.municipality_id" required>
                        <template v-for="m in municipalityStore.items">
                            <option
                                v-if="(m.config?.access_role||0)>=user.role"
                                :key="m.id" :value="m.id">
                                {{ m.name }}
                            </option>
                        </template>
                    </select>
                    <label>Муниципалитет</label>
                </div>


            </template>
            <template v-if="step===2">
                <h5 class="mb-2">Какая у вас проблемная ситуация?</h5>
                <IssueSelector
                    v-model="form.problems"
                    :issues="problems"></IssueSelector>
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
                    <textarea class="form-control" style="height: 120px" v-model="form.problem_description" required minlength="10"></textarea>
                    <label>Особенности проблемы *</label>
                </div>

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
                            class="btn btn-outline-primary w-100 p-3"
                            @click="addHelpFormat"
                            :disabled="!hasAvailableHelpFormats">
                        Добавить формат помощи
                    </button>
                </div>


                <!-- to_user_id -->
                <!--        <div class="form-floating mb-2">
                            <select class="form-select" v-model="form.to_user_id">
                                <option v-for="u in officials" :key="u.id" :value="u.id">
                                    {{ u.name }}
                                </option>
                            </select>
                            <label>Официальное лицо</label>
                        </div>-->


                <!-- received_at -->
                <div class="form-floating mb-2">
                    <input type="date" class="form-control" v-model="form.received_at">
                    <label>Дата получения</label>
                </div>


                <!-- help_formats dynamic -->

                <!-- comment -->
                <div class="form-floating mb-2">
                    <textarea class="form-control" style="height: 100px" v-model="form.comment"></textarea>
                    <label>Комментарий</label>
                </div>

                <FileUploader v-model="form.documents"></FileUploader>


                <AudioRecorder v-model="form.audio_files"></AudioRecorder>


            </template>

            <template v-if="step===6">
                <div class="card shadow-sm border-light-subtle mb-2">
                    <div class="card-header fw-bold">
                        Сводная информация
                    </div>

                    <div class="card-body p-0 ">

                        <ul class="list-group list-group-flush">

                            <!-- От кого -->
                            <li class="list-group-item" v-if="form.received_from">
                                <strong>Ф.И.О.:</strong>
                                <span class="ms-2">{{ form.received_from }}</span>
                            </li>

                            <!-- Телефон -->
                            <li class="list-group-item" v-if="form.phone">
                                <strong>Телефон:</strong>
                                <span class="ms-2">{{ form.phone }}</span>
                            </li>

                            <!-- Муниципалитет -->
                            <li class="list-group-item" v-if="form.municipality_id">
                                <strong>Муниципалитет:</strong>
                                <span class="ms-2">{{ municipalityStore.byId(form.municipality_id).name }}</span>
                            </li>

                            <!-- Проблемы -->
                            <li class="list-group-item" v-if="hasIssueSelections(form.problems)">
                                <strong>Проблемы:</strong>
                                <ul class="mt-1">
                                    <template v-for="(item, i) in form.problems">
                                        <li :key="i" v-if="item">
                                            <h6 class="fw-bold small">{{ issueStore.byId(i).name }}</h6>
                                            <p class="mb-1 small" v-for="p in item">{{ p }}</p>
                                        </li>
                                    </template>

                                </ul>
                            </li>
                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">
                                    Проблемы не указаны
                                </p>
                            </li>

                            <!-- Описание проблемы -->
                            <li class="list-group-item" v-if="form.problem_description">
                                <strong>Особенности проблемы:</strong>
                                <div class="mt-1 text-muted">
                                    {{ form.problem_description }}
                                </div>
                            </li>


                            <!-- Решения -->
                            <li class="list-group-item" v-if="hasIssueSelections(form.solutions)">
                                <strong>Предложенные решения:</strong>
                                <ul class="mt-1">
                                    <template v-for="(item, i) in form.solutions">
                                        <li :key="i" v-if="item">
                                            <h6 class="fw-bold small">{{ issueStore.byId(i).name }}</h6>
                                            <p class="mb-1 small" v-for="p in item">{{ p }}</p>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">
                                    Предложений по решению проблемы не поступило
                                </p>
                            </li>


                            <!-- Трудности -->
                            <li class="list-group-item" v-if="hasIssueSelections(form.difficulties)">
                                <strong>Трудности:</strong>
                                <ul class="mt-1">
                                    <template v-for="(item, i) in form.difficulties">
                                        <li :key="i" v-if="item">
                                            <h6 class="fw-bold small">{{ issueStore.byId(i).name }}</h6>
                                            <p class="mb-1 small" v-for="p in item">{{ p }}</p>
                                        </li>
                                    </template>
                                </ul>
                            </li>

                            <li class="list-group-item" v-else>
                                <p class="alert alert-info mb-0">
                                    Трудностей реализации не обнаружено
                                </p>
                            </li>

                            <!-- Форматы помощи -->
                            <li class="list-group-item" v-if="form.help_formats.length">
                                <strong>Форматы помощи:</strong>
                                <ul class="mt-1">
                                    <li v-for="(item, i) in form.help_formats" :key="i">
                                        <p class="mb-1 small"> {{ helpStore.byId(item).name }}</p>
                                    </li>
                                </ul>
                            </li>

                            <!-- Комментарий -->
                            <li class="list-group-item" v-if="form.comment">
                                <strong>Комментарий к задаче:</strong>
                                <div class="mt-1 text-muted">
                                    {{ form.comment }}
                                </div>
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
                                class="btn btn-light p-3 border-light-subtle">Назад
                        </button>
                        <button type="submit"
                                class="btn btn-primary p-3">Вперед
                        </button>
                    </template>
                    <template v-if="step===1">
                        <button type="submit"
                                class="btn btn-primary p-3">Приступить
                        </button>
                    </template>

                    <template v-if="step===6">
                        <button
                            type="submit"
                            class="btn btn-primary p-3"
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
                received_at: "",
                documents: [],
                received_from: "",
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
        }
    },

    mounted() {


        this.municipalityStore.fetchAll()
        this.issueStore.fetchAll()
        this.helpStore.fetchAll()

        const today = new Date()
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset())

        const formatted = today.toISOString().slice(0, 10)

        this.form.received_at = formatted;
    },
    methods: {
        isGuest() {
            return (this.user?.role ?? 0) === 0
        },

        nextStep() {
            if (this.isGuest() && this.step === 2) {
                this.step = 5
                return
            }
            this.step++
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
                    ? new File([blob], `audio-${index}.webm`, { type: blob.type || 'audio/webm' })
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
                        query: { report: result.report_id },
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
