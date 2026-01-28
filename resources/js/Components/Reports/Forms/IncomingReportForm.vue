<script setup>
import IssueSelector from "@/Components/Issues/IssueSelector.vue";
import AudioRecorder from "@/Components/Reports/Modules/AudioRecorder.vue";
import FileUploader from "@/Components/Reports/Modules/FileUploader.vue";
</script>
<template>
    <form @submit.prevent="submitForm" class="p-3 border rounded">
        <!-- Прогресс -->
        <div class="my-3">
            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    :style="{ width: (step / maxStep * 100) + '%' }"
                ></div>
            </div>
            <p class="text-center mt-2">Шаг {{ step+1 }} из {{ maxStep+1 }}</p>
        </div>
        <template v-if="step===0">
            <h5 class="mb-2">Регистрация жалобы</h5>

            <template v-if="user.role === 0">
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
            </template>

            <div class="form-floating mb-2">
                <select class="form-select" v-model="form.municipality_id" required>
                    <option v-for="m in municipalityStore.items" :key="m.id" :value="m.id">
                        {{ m.name }}
                    </option>
                </select>
                <label>Муниципалитет</label>
            </div>


        </template>
        <template v-if="step===1">
            <h5 class="mb-2">Какая у вас проблемная ситуация?</h5>
            <IssueSelector
                v-model="form.problems"
                :issues="problems"></IssueSelector>
        </template>
        <template v-if="step===2">
            <h5 class="mb-2">Способы решения проблемы</h5>
            <IssueSelector
                v-model="form.solutions"
                :issues="solutions"></IssueSelector>
        </template>
        <template v-if="step===3">
            <h5 class="mb-2">Какие сложности возникли?</h5>
            <IssueSelector
                v-model="form.difficulties"
                :issues="difficulties"></IssueSelector>
        </template>
        <template v-if="step===4">
            <!-- REPORT SECTION -->
            <h5 class="mb-2">Дополнительная информация</h5>


            <div class="form-floating mb-2">
                <textarea class="form-control" style="height: 120px" v-model="form.problem_description"></textarea>
                <label>Особенности проблемы</label>
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

        <template v-if="step===5">
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
                        <li class="list-group-item" v-if="form.problems.length">
                            <strong>Проблемы:</strong>
                            <ul class="mt-1">
                                <template v-for="(item, i) in form.problems" >
                                    <li :key="i" v-if="item">
                                        <h6 class="fw-bold small">{{issueStore.byId(i).name}}</h6>
                                        <p class="mb-1 small" v-for="p in item">{{p}}</p>
                                    </li>
                                </template>

                            </ul>
                        </li>
                        <div class="p-2" v-else>
                            <p class="alert alert-info mb-0" >
                                Проблемы не указаны текстом
                            </p>
                        </div>

                        <!-- Описание проблемы -->
                        <li class="list-group-item" v-if="form.problem_description">
                            <strong>Особенности проблемы:</strong>
                            <div class="mt-1 text-muted">
                                {{ form.problem_description }}
                            </div>
                        </li>




                        <!-- Решения -->
                        <li class="list-group-item" v-if="form.solutions.length">
                            <strong>Предложенные решения:</strong>
                            <ul class="mt-1">
                                <template v-for="(item, i) in form.solutions" >
                                    <li :key="i" v-if="item">
                                        <h6 class="fw-bold small">{{issueStore.byId(i).name}}</h6>
                                        <p class="mb-1 small" v-for="p in item">{{p}}</p>
                                    </li>
                                </template>
                            </ul>
                        </li>
                        <div class="p-2" v-else>
                        <p class="alert alert-info mb-0" >
                            Предложений ро решению проблемы не поступило
                        </p>
                        </div>


                        <!-- Трудности -->
                        <li class="list-group-item" v-if="form.difficulties.length">
                            <strong>Трудности:</strong>
                            <ul class="mt-1">
                                <template v-for="(item, i) in form.difficulties" >
                                    <li :key="i" v-if="item">
                                        <h6 class="fw-bold small">{{issueStore.byId(i).name}}</h6>
                                        <p class="mb-1 small" v-for="p in item">{{p}}</p>
                                    </li>
                                </template>
                            </ul>
                        </li>

                        <div class="p-2" v-else>
                            <p class="alert alert-info mb-0">
                                Трудностей реализации не обнаружено
                            </p>
                        </div>

                        <!-- Форматы помощи -->
                        <li class="list-group-item" v-if="form.help_formats.length">
                            <strong>Форматы помощи:</strong>
                            <ul class="mt-1">
                                <li v-for="(item, i) in form.help_formats" :key="i">
                                   <p class="mb-1 small"> {{helpStore.byId(item).name}}</p>
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
            <div class="btn-group w-100" role="group" aria-label="Basic example">
                <template v-if="step>0&&step<5">
                    <button type="button"
                            @click="step--"
                            class="btn btn-light p-3">Назад
                    </button>
                    <button type="submit"
                            class="btn btn-primary p-3">Вперед
                    </button>
                </template>
                <template v-if="step===0">
                    <button type="submit"
                            class="btn btn-primary p-3">Приступить
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
import {useMunicipalitiesStore} from "@/stores/useMunicipalitiesStore";
import {useIssueCategoriesStore} from "@/stores/useIssueCategoriesStore";
import {useUsersStore} from "@/stores/users";
import {useHelpFormatsStore} from "@/stores/useHelpFormatsStore";

export default {
    name: "ReportForm",
    components: {},


    data() {
        return {
            step: 0,
            maxStep: 5,
            isRecording: false,
            mediaRecorder: null,
            audioChunks: [],
            helpStore: useHelpFormatsStore(),
            userStore: useUsersStore(),
            municipalityStore: useMunicipalitiesStore(),
            issueStore: useIssueCategoriesStore(),
            selected_problem: null,
            form: {
                type: 0,
                phone: null,
                from_user_id: "",
                to_user_id: "",
                municipality_id: "",
                received_at: "",
                documents: [],
                received_from: "",
                problem_description: "",
                help_formats: [""],
                comment: "",
                problems: [],
                solutions: [],
                difficulties: [],
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
            return this.userStore?.self || null
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


        submitForm() {
            this.step++;
            if (this.step<5)
                return;
            // Здесь отправка формы
            console.log("FORM DATA:", this.form);
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
