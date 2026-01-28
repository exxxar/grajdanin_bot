<script setup>
import Pagination from "@/Components/Pagination.vue";
</script>
<template>


    <h5 class="mb-2 fw-bold">Типы помощи</h5>
    <!-- LIST GROUP -->
    <ul class="list-group mb-3">

        <li v-for="item in paginated"
            :key="item.id"
            class="list-group-item d-flex justify-content-between align-items-center">

            <!-- LEFT SIDE -->
            <div class="flex-grow-1">

                <p class="fw-bold mb-0">
                    # {{ item.id }} {{ item.name }}
                </p>

                <span v-if="Object.keys(item.config).length > 0"
                      class="badge bg-secondary mt-1">
                        {{ Object.keys(item.config).length }} параметра
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
    name: "HelpFormatList",

    data() {
        return {
            page: 1,
            perPage: 5,

            items: [
                {id: 1, name: "Разработка и организация мероприятия", config: {level: "high", category: "event"}},
                {id: 2, name: "Закупка материалов", config: {budget: "required"}},
                {id: 3, name: "Разработка дизайна", config: {}},
                {id: 4, name: "Предоставление оборудования", config: {type: "hardware", count: "variable"}},
                {id: 5, name: "Привлечение актива", config: {}},
                {id: 6, name: "Иное", config: {}}
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
            if (!confirm("Удалить этот тип помощи?")) return;

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

@media (max-width: 576px) {
    .list-group-item {
        font-size: 14px;
    }
}
</style>
