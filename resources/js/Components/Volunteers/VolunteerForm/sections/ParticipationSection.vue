<template>

    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">
                Готовность к участию
            </h5>

            <div class="form-floating mb-2">
                <input
                    v-model="model.timePerWeek"
                    class="form-control"
                    placeholder="Часы"
                >
                <label>
                    Часов в неделю
                </label>
            </div>

            <div class="form-floating mb-2">

                <select
                    v-model="model.participationFormat"
                    class="form-select"
                >
                    <option>
                        Онлайн
                    </option>

                    <option>
                        Офлайн
                    </option>

                    <option>
                        Смешанный
                    </option>
                </select>

                <label>
                    Формат участия
                </label>

            </div>

            <div class="form-floating mb-2">

                <select
                    v-model="model.canTravel"
                    class="form-select"
                >
                    <option>
                        Да
                    </option>

                    <option>
                        Нет
                    </option>
                </select>

                <label>
                    Возможность выезда
                </label>

            </div>

            <div class="form-floating mb-3">

                <select
                    v-model="model.weekendWork"
                    class="form-select"
                >
                    <option>
                        Да
                    </option>

                    <option>
                        Нет
                    </option>
                </select>

                <label>
                    Работа по выходным
                </label>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Направления деятельности
                </label>

                <div
                    v-for="dir in directionsList"
                    :key="dir"
                    class="form-check"
                >

                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="dir"
                        v-model="model.directions"
                    >

                    <label class="form-check-label">
                        {{ dir }}
                    </label>

                </div>

            </div>

            <div class="form-floating mb-2">

                <input
                    v-model="model.otherDirection"
                    class="form-control"
                    placeholder="Другое"
                >

                <label>
                    Другое направление
                </label>

            </div>

            <div class="form-floating mb-2">

                <select
                    v-model="model.clothingSize"
                    class="form-select"
                >
                    <option
                        v-for="size in clothingSizes"
                        :key="size"
                    >
                        {{ size }}
                    </option>
                </select>

                <label>
                    Размер одежды
                </label>

            </div>

            <div class="form-check">

                <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="model.sendTasks"
                    id="sendTasks"
                >

                <label
                    class="form-check-label"
                    for="sendTasks"
                >
                    Присылать задания
                </label>

            </div>

        </div>
    </div>

</template>

<script>
export default {

    props: {
        modelValue: Object,
        directionsList: Array,
        clothingSizes: Array
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
