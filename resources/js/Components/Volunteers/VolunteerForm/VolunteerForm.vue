<template>

    <form @submit="handleSubmit" class="container p-3">


        <template v-if="currentSection==='sections'">
            <div class="card mb-2 rounded-4">
                <div class="card-body">
                    <VolunteerProgress
                        :progress="totalProgress"
                    />

                    <p class="alert alert-light border-info my-2 small">
                        До момента отправки ваши данные будут сохранены как <span class="fw-bold">черновик</span> на вашем устройстве
                    </p>
                </div>
            </div>

            <VolunteerSections
                :sections="sections"
                :current="currentSection"
                :get-progress="sectionProgress"
                @select="currentSection = $event"

            />

            <div class="card my-2">
                <div class="card-body">
                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.agreePersonal"
                            id="agreePersonal"
                        >

                        <label
                            class="form-check-label"
                            for="agreePersonal"
                        >
                            Согласие на обработку персональных данных
                        </label>

                        <div class="text-danger">
                            {{ errors.agreePersonal }}
                        </div>

                    </div>

                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.agreeNotifications"
                            id="agreeNotifications"
                        >

                        <label
                            class="form-check-label"
                            for="agreeNotifications"
                        >
                            Согласие на уведомления
                        </label>

                    </div>

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.agreeEvents"
                            id="agreeEvents"
                        >

                        <label
                            class="form-check-label"
                            for="agreeEvents"
                        >
                            Согласие на участие в мероприятиях
                        </label>

                    </div>

                    <div
                        v-if="!canSend"
                        style="line-height:100%;"
                        class="alert alert-light border-danger mt-2 mb-2">

                        <p v-if="!isPersonalValid" class="mb-2"><i class="fa-solid fa-xmark text-danger"></i> Вы не
                            указали персональные данные</p>
                        <p v-if="totalProgress<50" class="mb-2"><i class="fa-solid fa-xmark text-danger"></i> Вы должны
                            заполнить не меньше 50% данных о себе</p>
                        <p v-if="!form.agreeNotifications" class="mb-2"><i class="fa-solid fa-xmark text-danger"></i> Вы
                            не дали согласие на оповещения</p>
                        <p v-if="!form.agreePersonal" class="mb-2"><i class="fa-solid fa-xmark text-danger"></i> Вы не
                            дали согласие на обработку данных</p>
                        <p v-if="!form.agreeEvents" class="mb-2"><i class="fa-solid fa-xmark text-danger"></i> Вы не
                            дали согласие на участие в мероприятиях</p>
                    </div>
                    <button
                        :disabled="!canSend"
                        type="submit" class="btn btn-primary p-2 mt-2 w-100 rounded-4">Отправить
                    </button>
                </div>
            </div>
        </template>


        <template v-if="currentSection!=='sections'">

            <!--            <HorizontalVolunteerSections
                            :sections="sections"
                            :current="currentSection"
                            :get-progress="sectionProgress"
                            @select="currentSection = $event"
                        ></HorizontalVolunteerSections>-->

            <PersonalSection
                v-if="currentSection==='personal'"
                v-model="form"
                :errors="errors"
            />

            <ContactsSection
                :errors="errors"
                v-if="currentSection==='contacts' "
                v-model="form"
            />

            <SocialSection
                v-if="currentSection==='social' "
                v-model="form"
            />

            <ProfessionalSection
                v-if="currentSection==='professional' "
                :skills-list="skillsList"
                v-model="form"
            />

            <ParticipationSection
                v-if="currentSection==='participation' "
                :directions-list="directionsList"
                :clothing-sizes="clothingSizes"
                v-model="form"
            />

            <AdditionalSection
                v-if="currentSection==='additional' "
                v-model="form"
                :errors="errors"
                :health-options="healthOptions"
            />

            <div class="card mt-2">
                <div class="card-body">
                    <div class="d-flex w-100 btn-group">
                        <button
                            type="button"
                            @click="back"
                            class="btn btn-light p-2 rounded-start-4"
                        >
                            К секциям
                        </button>

                        <button
                            type="button"
                            @click="next"
                            class="btn btn-primary p-2 rounded-end-4"

                        >
                            Вперед <span>{{ step  }}</span> / <span>{{ sections.length }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>


    </form>

</template>
<script>
import {useJobStore} from "@/stores/useJobStore";

import PersonalSection from "./sections/PersonalSection.vue";
import ContactsSection from "./sections/ContactsSection.vue";
import SocialSection from "./sections/SocialSection.vue";
import ProfessionalSection from "./sections/ProfessionalSection.vue";
import ParticipationSection from "./sections/ParticipationSection.vue";
import AdditionalSection from "./sections/AdditionalSection.vue";

import VolunteerProgress from "./VolunteerProgress.vue";
import VolunteerSections from "./VolunteerSections.vue";
import HorizontalVolunteerSections from "./HorizontalVolunteerSections.vue";

import sectionsConfig from "./config/volunteerSections";
import createVolunteerForm from "./data/defaultVolunteerForm";
import {useVolunteerProgress} from "./composables/useVolunteerProgress";

export default {
    name: "VolunteerForm",

    components: {
        PersonalSection,
        ContactsSection,
        SocialSection,
        ProfessionalSection,
        ParticipationSection,
        AdditionalSection,
        VolunteerProgress,
        HorizontalVolunteerSections,
        VolunteerSections
    },

    data() {
        return {
            jobStore: useJobStore(),

            currentSection: "sections",

            sections: sectionsConfig,

            form: createVolunteerForm(),

            step: 0,
            errors: {},

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
                    id: 1,
                    icon: "fa-solid fa-head-side-mask",
                    label: "Часто болею",
                    value: "болею"
                },
                {
                    id: 2,
                    icon: "fa-solid fa-ear-deaf",
                    label: "Плохо слышит / говорит",
                    value: "плохо слышит или говорит"
                },
                {
                    id: 3,
                    icon: "fa-solid fa-glasses",
                    label: "Слабовидящий",
                    value: "слабовидящий"
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
                    label: "Аллергия",
                    value: "аллергия"
                }
            ]
        };
    },

    computed: {
        canSend() {
            return this.totalProgress >= 50 &&
                this.isPersonalValid &&
                this.form.agreeEvents &&
                this.form.agreePersonal &&
                this.form.agreeNotifications
        },
        isPersonalValid() {
            const f = this.form;


            return (
                f.fio?.last &&
                f.fio?.first &&
                f.birthDate
            );
        },
        sectionProgress() {
            return useVolunteerProgress(this.form).sectionProgress;
        },

        next() {
            const max = this.sections.length
            if (this.step < max)
                this.step++

            if (this.step === max)
                this.currentSection = "sections"

            if (this.step < max)
                this.currentSection = this.sections[this.step].id

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });



            /*const element = document.querySelector("#"+this.currentSection)
            element.scrollIntoView({
                behavior: 'smooth',
                inline: 'center'
            });*/
        },
       back(){
           this.currentSection = "sections"
           window.scrollTo({
               top: 0,
               behavior: 'smooth'
           });
       },
        totalProgress() {
            const values = this.sections.map(
                s => this.sectionProgress(s.id)
            );

            return Math.round(
                values.reduce((a, b) => a + b, 0) / values.length
            );
        }

    },

    methods: {
        validateStep() {
            this.errors = {};

            if (this.step === 1) {
                if (!this.form.fio.last)
                    this.errors.lastName = "Введите фамилию";

                if (!this.form.fio.first)
                    this.errors.firstName = "Введите имя";

                if (!this.form.birthDate)
                    this.errors.birthDate = "Укажите дату рождения";
            }

            if (this.step === 2) {
                if (!this.form.agreePersonal)
                    this.errors.agreePersonal = "Необходимо согласие";
            }

            return Object.keys(this.errors).length === 0;
        },

        handleSubmit(e) {
            e.preventDefault();

            // 1. первый экран — личные данные
            if (this.step === 1) {
                if (!this.validateStep()) return;

                this.step = 2;
                return;
            }

            // 2. финальная проверка
            if (!this.form.agreePersonal) {
                this.currentSection = "additional";
                this.errors.agreePersonal = "Необходимо согласие";
                return;
            }

            // 3. отправка
            console.log("FORM:", this.form);

            this.jobStore.createVolunteerPdfReport(this.form);

            this.$emit("success");
        }
    }
};
</script>
