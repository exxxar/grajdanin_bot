<template>

    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">
                Дополнительные сведения
            </h5>

            <div class="form-floating mb-2">

            <textarea
                v-model="model.motivation"
                class="form-control"
                style="height:120px"
            ></textarea>

                <label>
                    Почему хотите стать волонтёром
                </label>

            </div>

            <div class="form-floating mb-3">

            <textarea
                v-model="model.health"
                class="form-control"
                style="height:120px"
            ></textarea>

                <label>
                    Ограничения по здоровью
                </label>

            </div>

            <h6>
                Ограничения здоровья
            </h6>

            <div class="list-group mb-3">

                <button
                    type="button"
                    class="list-group-item list-group-item-action"
                    :class="{
                    'bg-primary text-white':model.healthLimit==='none'
                }"
                    @click="
                    model.healthLimit='none';
                    model.healthDetails=[];
                "
                >
                    Нет ограничений
                </button>

                <button
                    type="button"
                    class="list-group-item list-group-item-action"
                    :class="{
                    'bg-primary text-white':model.healthLimit==='has'
                }"
                    @click="
                    model.healthLimit='has'
                "
                >
                    Есть ограничения
                </button>

            </div>

            <div
                v-if="model.healthLimit==='has'"
                class="mb-3"
            >

                <div
                    v-for="item in healthOptions"
                    :key="item.id"
                    class="form-check"
                >

                    <input
                        class="form-check-input"
                        type="checkbox"
                        :id="'health'+item.value"
                        :value="item.value"
                        v-model="model.healthDetails"
                    >

                    <label
                        :for="'health'+item.value"
                        class="form-check-label">
                        {{ item.label }}
                    </label>

                </div>

            </div>

            <div class="form-floating mb-2">

            <textarea
                v-model="model.comment"
                class="form-control"
                style="height:120px"
            ></textarea>

                <label>
                    Комментарий
                </label>

            </div>


        </div>
    </div>

</template>

<script>
export default {

    props: {
        modelValue: Object,
        healthOptions: Array,
        errors: Object
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
