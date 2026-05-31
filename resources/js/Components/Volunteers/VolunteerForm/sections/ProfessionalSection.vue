<template>

    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">
                Профессиональная информация
            </h5>

            <div class="form-floating mb-2">
                <input
                    v-model="model.profession"
                    class="form-control"
                    placeholder="Профессия"
                >
                <label>Профессия</label>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Навыки
                </label>

                <div
                    v-for="skill in skillsList"
                    :key="skill"
                    class="form-check"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="skill"
                        v-model="model.skills"
                    >

                    <label class="form-check-label">
                        {{ skill }}
                    </label>
                </div>

            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.languages"
                    class="form-control"
                    placeholder="Языки"
                >
                <label>Иностранные языки</label>
            </div>

            <div class="form-floating mb-2">
                <select
                    v-model="model.hasExperience"
                    class="form-select"
                >
                    <option :value="null">
                        Не выбрано
                    </option>

                    <option :value="true">
                        Да
                    </option>

                    <option :value="false">
                        Нет
                    </option>
                </select>

                <label>
                    Опыт волонтёрства
                </label>
            </div>

            <div
                v-if="model.hasExperience"
                class="form-floating mb-2"
            >
                <input
                    v-model="model.experience"
                    class="form-control"
                    placeholder="Лет опыта"
                >

                <label>
                    Сколько лет опыта
                </label>
            </div>

            <div class="form-floating mb-2">

            <textarea
                v-model="model.experienceDetails"
                class="form-control"
                style="height:120px"
            ></textarea>

                <label>
                    Какие задачи выполняли
                </label>

            </div>

            <div class="form-floating">

                <select
                    v-model="model.readyToLearn"
                    class="form-select"
                >
                    <option value="">
                        Не выбрано
                    </option>

                    <option>
                        Да
                    </option>

                    <option>
                        Нет
                    </option>
                </select>

                <label>
                    Готовность к обучению
                </label>

            </div>

        </div>
    </div>

</template>

<script>
export default {

    props: {
        modelValue: Object,
        skillsList: Array
    },

    emits: ['update:modelValue'],

    computed: {

        model: {

            get() {
                return this.modelValue
            },

            set(value) {
                this.$emit(
                    'update:modelValue',
                    value
                )
            }

        }

    }

}
</script>
