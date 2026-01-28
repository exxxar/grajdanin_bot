<template>
    <form @submit.prevent="submitForm">

        <h5 class="mb-3">
            {{ isEdit ? "Редактирование типа помощи" : "Добавление типа помощи" }}
        </h5>

        <!-- NAME -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" v-model="form.name">
            <label>Название типа помощи</label>
        </div>

        <h6 class="mb-2">Конфигурация (ключ → значение)</h6>

        <template v-for="(item, index) in form.config" :key="index">

            <div class="mb-2 fw-bold d-flex justify-content-between align-items-center">
                <span>Параметр #{{ index + 1 }}</span>

                <span class="btn btn-link btn-sm"
                      @click="removeConfig(index)">
                    Удалить
                </span>
            </div>

            <div class="form-floating mb-2">
                <input type="text"
                       class="form-control"
                       v-model="item.key">
                <label>Ключ</label>
            </div>

            <div class="form-floating">
                <input type="text"
                       class="form-control"
                       v-model="item.value">
                <label>Значение</label>
            </div>

        </template>

        <button type="button"
                class="btn btn-light btn-sm w-100 my-3"
                @click="addConfig">
            Добавить параметр
        </button>

        <button
            style="position: sticky; bottom: 20px;"
            class="btn btn-primary w-100 mt-3 p-3">
            Сохранить
        </button>

    </form>
</template>

<script>
export default {
    name: "HelpFormatForm",

    props: {
        modelValue: {
            type: Object,
            default: () => ({name: "", config: {}})
        }
    },

    data() {
        return {
            form: {
                name: "",
                config: []
            }
        };
    },

    computed: {
        isEdit() {
            return !!this.modelValue?.id;
        }
    },

    watch: {
        modelValue: {
            immediate: true,
            deep: true,
            handler(val) {

                if (!val)
                    return
                // Преобразуем config-объект в массив [{key, value}]
                const cfg = Object.entries(val.config || {}).map(([key, value]) => ({
                    key,
                    value
                }));

                this.form = {
                    name: val.name || "",
                    config: cfg
                };
            }
        },

        form: {
            deep: true,
            handler() {
                // Эмитим обновлённое значение наружу
                const payload = {
                    ...this.form,
                    config: this.form.config.reduce((acc, item) => {
                        if (item.key.trim() !== "") {
                            acc[item.key] = item.value;
                        }
                        return acc;
                    }, {})
                };

                this.$emit("update:modelValue", payload);
            }
        }
    },

    methods: {
        addConfig() {
            this.form.config.push({key: "", value: ""});
        },

        removeConfig(index) {
            this.form.config.splice(index, 1);
        },

        submitForm() {
            const payload = {
                ...this.form,
                config: this.form.config.reduce((acc, item) => {
                    if (item.key.trim() !== "") {
                        acc[item.key] = item.value;
                    }
                    return acc;
                }, {})
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
