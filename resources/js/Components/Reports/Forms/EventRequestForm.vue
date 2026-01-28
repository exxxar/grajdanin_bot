
<script setup>
import AudioRecorder from "@/Components/Reports/Modules/AudioRecorder.vue";
import FileUploader from "@/Components/Reports/Modules/FileUploader.vue";
</script>

<template>
    <form class="p-3" @submit.prevent="submitForm">

        <h5 class="mb-2">Заявка на мероприятие</h5>

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

        <!-- PARTICIPANTS COUNT -->
        <div class="form-floating mb-2">
            <input type="number" class="form-control" v-model="form.participants_count">
            <label>Количество участников</label>
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

        <!-- COMMENT -->
        <div class="form-floating mb-2">
            <textarea class="form-control" style="height: 100px" v-model="form.comment"></textarea>
            <label>Комментарий</label>
        </div>

        <FileUploader v-model="form.documents"></FileUploader>


        <AudioRecorder v-model="form.audio_files"></AudioRecorder>

        <button class="btn btn-primary w-100 p-3">Отправить</button>
    </form>
</template>

<script>
import {useHelpFormatsStore} from "@/stores/useHelpFormatsStore";
import {useUsersStore} from "@/stores/users";
import {useMunicipalitiesStore} from "@/stores/useMunicipalitiesStore";
import {useIssueCategoriesStore} from "@/stores/useIssueCategoriesStore";

export default {
    name: "EventRequestForm",

    data() {
        return {
            helpStore: useHelpFormatsStore(),
            userStore: useUsersStore(),
            municipalityStore: useMunicipalitiesStore(),
            issueStore: useIssueCategoriesStore(),
            form: {
                event_date: "",
                description: "",
                target_audience: "",
                participants_count: "",
                help_formats: [""],
                comment: ""
            },


        };
    },
    computed: {
        user() {
            return this.userStore?.self || null
        },
        hasAvailableHelpFormats() {
            return this.helpStore.items.length > this.form.help_formats.length
        },
    },
    mounted() {


        this.municipalityStore.fetchAll()
        this.issueStore.fetchAll()
        this.helpStore.fetchAll()

        const today = new Date()
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset())

        const formatted = today.toISOString().slice(0, 10)

        this.form.event_date = formatted;
    },
    methods: {
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
            console.log("FORM:", this.form);
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
