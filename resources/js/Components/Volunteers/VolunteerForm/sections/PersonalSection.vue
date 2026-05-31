<template>

    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">Личные данные</h5>

            <div class="form-floating mb-2">
                <input
                    v-model="model.fio.last"
                    class="form-control"
                    placeholder="Фамилия"
                >
                <label>Фамилия</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.fio.first"
                    class="form-control"
                    placeholder="Имя"
                >
                <label>Имя</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.fio.middle"
                    class="form-control"
                    placeholder="Отчество"
                >
                <label>Отчество</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    type="date"
                    v-model="model.birthDate"
                    class="form-control"
                >
                <label>Дата рождения</label>
            </div>

            <div class="form-floating mb-2">
                <select
                    v-model="model.gender"
                    class="form-select"
                >
                    <option value="">Не выбрано</option>
                    <option value="1">Мужской</option>
                    <option value="0">Женский</option>
                </select>
                <label>Пол</label>
            </div>

            <div class="form-floating">
                <input
                    v-model="model.citizenship"
                    class="form-control"
                    placeholder="Гражданство"
                >
                <label>Гражданство</label>
            </div>

        </div>
    </div>

</template>

<script>
export default {

    props: {
        modelValue: {
            type: Object,
            required: true
        }
    },

    emits: ['update:modelValue'],

    computed: {

        model: {

            get() {
                return this.modelValue;
            },

            set(value) {
                this.$emit('update:modelValue', value);
            }

        }

    }

};
</script>
