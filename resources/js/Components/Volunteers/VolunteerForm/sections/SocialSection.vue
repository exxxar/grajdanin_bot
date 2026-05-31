<template>

    <div class="card">

        {{}}
        <div class="card-body">

            <h5>
                Соцсети
            </h5>

            <div class="form-floating mb-2">
                <input
                    v-model="model.telegram"
                    class="form-control"
                >
                <label>Telegram</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.whatsapp"
                    class="form-control"
                >
                <label>WhatsApp</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.vk"
                    class="form-control"
                >
                <label>VK / Facebook</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    v-model="model.website"
                    class="form-control"
                >
                <label>Сайт</label>
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
