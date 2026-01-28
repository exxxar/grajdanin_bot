<script setup>
import IconPicker from '../Icons/IconPicker.vue'
</script>
<template>
    <form @submit.prevent="submitForm">

        <h5 class="mb-2">
            {{ isEdit ? "Редактирование категории" : "Добавление категории" }}
        </h5>

        <!-- NAME -->
        <div class="form-floating mb-2">
            <input type="text" class="form-control" v-model="form.name">
            <label>Название категории</label>
        </div>

        <!-- DESCRIPTION -->
        <div class="form-floating mb-2">
            <textarea class="form-control" style="height: 100px"
                      v-model="form.description"></textarea>
            <label>Описание</label>
        </div>

        <!-- TYPE -->
        <div class="form-floating mb-2">
            <select class="form-select" v-model="form.type">
                <option :value="0">Проблемы</option>
                <option :value="1">Решения</option>
                <option :value="2">Сложности</option>
            </select>
            <label>Тип категории</label>
        </div>

        <!-- ICON PICKER -->
        <div class="mb-2">
            <label class="form-label">Иконка <span class="fst-italic">{{form.icon}}</span></label>
            <!-- твой пикер -->
            <IconPicker v-model="form.icon" />
        </div>

        <!-- VARIANTS -->
        <h6 class="mb-2">Варианты (список подкатегорий)</h6>

        <template v-for="(item, index) in form.variants" :key="index">

            <div class="mb-2 fw-bold d-flex justify-content-between align-items-center">
                <span>Вариант #{{ index + 1 }}</span>

                <span class="btn btn-link btn-sm"
                      @click="removeVariant(index)">
                    Удалить
                </span>
            </div>

            <div class="form-floating mb-2">
                <input type="text"
                       class="form-control"
                       v-model="form.variants[index]">
                <label>Название варианта</label>
            </div>

        </template>

        <button type="button"
                class="btn btn-light btn-sm w-100 my-3"
                @click="addVariant">
            Добавить вариант
        </button>

        <button
            style="position: sticky; bottom: 20px;z-index:100;"
            class="btn btn-primary w-100 mt-3 p-3">
            Сохранить
        </button>

    </form>
</template>

<script>
export default {
    name: "IssueCategoryForm",

    props: {
        modelValue: {
            type: Object,
            default: () => ({
                name: "",
                description: "",
                type: 0,
                icon: "",
                variants: []
            })
        }
    },

    data() {
        return {
            form: {
                name: "",
                description: "",
                type: 0,
                icon: "",
                variants: []
            }
        };
    },

    computed: {
        isEdit() {
            return !!this.modelValue?.id;
        }
    },

    watch: {
        form: {
            deep: true,
            handler() {
                const payload = {
                    ...this.form,
                    variants: [...this.form.variants]
                };

                this.$emit("update:modelValue", payload);
            }
        }
    },

    mounted() {
        if (this.modelValue){

            this.form = {
                name: this.modelValue.name || "",
                description: this.modelValue.description || "",
                type: this.modelValue.type ?? 0,
                icon: this.modelValue.icon || "",
                variants: Array.isArray(this.modelValue.variants) ? [...this.modelValue.variants] : []
            };
        }
    },
    methods: {
        addVariant() {
            this.form.variants.push("");
        },

        removeVariant(index) {
            this.form.variants.splice(index, 1);
        },

        submitForm() {
            const payload = {
                ...this.form,
                variants: this.form.variants.filter(v => v.trim() !== "")
            };

            this.$emit("submit", payload);
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
