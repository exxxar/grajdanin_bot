<script setup>
import Pagination from "@/Components/Pagination.vue";
</script>
<template>

    <h5 class="mb-2 fw-bold">Категории ситуаций</h5>

    <!-- LIST GROUP -->
    <ul class="list-group mb-3">

        <li v-for="item in paginated"
            :key="item.id"
            class="list-group-item d-flex justify-content-between align-items-center">

            <!-- LEFT SIDE -->
            <div class="flex-grow-1">

                <p class="fw-bold mb-1 d-flex align-items-center">
                    <span v-html="item.icon" class="me-2"></span>
                    #{{ item.id }} {{ item.name }}
                </p>

                <p class="text-muted small mb-1" v-if="item.description">
                    {{ item.description }}
                </p>

                <span v-if="item.variants && item.variants.length > 0"
                      class="badge bg-secondary">
                        {{ item.variants.length }} варианта
                    </span>

            </div>

            <!-- DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown">
                    ⋮
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="javascript:void(0)"
                           @click="$emit('edit', item)">
                            ✎ Редактировать
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item text-danger"
                           href="javascript:void(0)"
                           @click="remove(item.id)">
                            🗑 Удалить
                        </a>
                    </li>

                </ul>
            </div>

        </li>

        <li v-if="paginated.length === 0"
            class="list-group-item text-center text-muted py-4">
            Нет данных
        </li>

    </ul>

    <Pagination :pagination="paginated"></Pagination>


</template>

<script>
export default {
    name: "IssueCategoryList",

    data() {
        return {
            page: 1,
            perPage: 5,

            // Пример данных (заменишь API)
            items: [
                {
                    id: 1,
                    name: "Образование",
                    description: "Проблемы школ, детских садов и образовательных учреждений.",
                    icon: '<i class="fa-solid fa-graduation-cap"></i>',
                    type: 0,
                    variants: [
                        "Школы и детские сады",
                        "Качество обучения",
                        "Питание",
                        "Инфраструктура учреждений"
                    ]
                },
                {
                    id: 2,
                    name: "Здравоохранение",
                    description: "Поликлиники, больницы, доступность врачей.",
                    icon: '<i class="fa-solid fa-hospital"></i>',
                    type: 0,
                    variants: ["Поликлиники", "Очереди", "Качество обслуживания"]
                },
                {
                    id: 3,
                    name: "Транспорт",
                    description: null,
                    icon: '<i class="fa-solid fa-bus"></i>',
                    type: 1,
                    variants: []
                }
            ]
        };
    },

    computed: {
        totalPages() {
            return Math.ceil(this.items.length / this.perPage);
        },

        paginated() {
            const start = (this.page - 1) * this.perPage;
            return this.items.slice(start, start + this.perPage);
        }
    },

    methods: {
        remove(id) {
            if (!confirm("Удалить категорию?")) return;

            this.items = this.items.filter(i => i.id !== id);

            if (this.page > this.totalPages) {
                this.page = this.totalPages || 1;
            }
        }
    }
};
</script>

<style scoped>
.list-group-item {
    border-radius: 8px;
    margin-bottom: 8px;
}

.dropdown-toggle::after {
    display: none;
}

.list-group-item i {
    font-size: 1.2rem;
}

@media (max-width: 576px) {
    .list-group-item {
        font-size: 14px;
    }
}
</style>
