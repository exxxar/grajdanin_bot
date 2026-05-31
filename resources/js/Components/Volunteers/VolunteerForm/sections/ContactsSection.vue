<template>

    <div class="card">

        <div class="card-body">

            <h5 class="mb-3">
                Контакты
            </h5>

            <div class="form-floating mb-2">
                <input
                    v-model="model.phone"
                    class="form-control"
                    placeholder="Телефон"
                >
                <label>Телефон</label>
            </div>

            <div class="text-danger">
                {{ errors.phone }}
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.email"
                    class="form-control"
                    placeholder="Email"
                >
                <label>Email</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.city"
                    class="form-control"
                    placeholder="Город"
                >
                <label>Город</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.address"
                    class="form-control"
                    placeholder="Адрес"
                >
                <label>Адрес</label>
            </div>

        </div>

    </div>

</template>

<script>
export default {

    props: {
        modelValue: Object,
        errors: Object
    },

    emits: ['update:modelValue'],

    computed: {

        model: {

            get() {
                return this.modelValue
            },

            set(v) {
                this.$emit(
                    'update:modelValue',
                    v
                )
            }

        }

    }

}
</script>
