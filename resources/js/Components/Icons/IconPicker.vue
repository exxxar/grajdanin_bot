<template>
    <div class="icon-picker">

        <!-- SEARCH -->
        <input
            type="text"
            class="form-control mb-2 p-3"
            placeholder="Поиск иконки…"
            v-model="search"
        />

        <!-- ICON GRID -->
        <div class="row g-2 icon-grid">
            <div
                v-for="(item, index) in visibleIcons"
                :key="index"
                class="col-3 col-md-2 text-center"
            >
                <button
                    type="button"
                    class="btn btn-light w-100 py-3 border"
                    :class="{ 'bg-primary text-white': check(item) }"
                    @click="selectIcon(item)"
                >
                    <FontAwesomeIcon :icon="item.icon" size="lg"/>
                </button>
            </div>
        </div>

        <!-- LOAD MORE -->
        <div v-if="!isSearching && visibleCount < filtered.length" class="text-center mt-3">
            <button
                type="button"
                class="btn btn-outline-primary btn-sm" @click="loadMore">
                Показать ещё
            </button>
        </div>

    </div>
</template>

<script>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {iconList} from './icons.js';

export default {
    name: "IconPicker",

    components: {FontAwesomeIcon},

    props: {
        modelValue: {
            type: String,
            default: ""
        }
    },

    emits: ["update:modelValue"],

    data() {
        return {
            search: "",
            visibleCount: 20 // показываем первые 20
        };
    },

    computed: {
        isSearching() {
            return this.search.trim().length > 0;
        },

        filtered() {
            const q = this.search.toLowerCase();
            return iconList.filter(i => {
                    return i.class.toLowerCase().indexOf(q) !== -1
                }
            );
        },

        visibleIcons() {
            // Если идёт поиск — показываем все найденные
            if (this.isSearching) {
                return this.filtered;
            }

            // Если нет поиска — показываем блоками по 20
            return this.filtered.slice(0, this.visibleCount);
        }
    },
    watch: {
        search() {
            this.visibleCount = 20;
        }
    },
    methods: {
        check(item) {
            const html = `<i class="${item.class}"></i>`;
            return this.modelValue === html
        },
        selectIcon(item) {
            const html = `<i class="${item.class}"></i>`;
            this.$emit("update:modelValue", html);
        },

        loadMore() {
            this.visibleCount += 20;
        }
    }
};
</script>

<style scoped>
.icon-grid {
    max-height: 300px;
    overflow-y: auto;
}
</style>
