<template>

    <div class="container mb-2 p-0">
        <!-- CATEGORY LIST -->
        <template v-if="selected === null">

            <!-- GLOBAL SEARCH -->
            <div class="form-floating mb-3">
                <input
                    type="text"
                    class="form-control"
                    id="globalSearchInput"
                    placeholder="Поиск по категориям"
                    v-model="globalSearch"
                >
                <label for="globalSearchInput">Поиск по категориям</label>
            </div>


            <!-- SEARCH RESULTS -->
            <div v-if="globalSearch.trim() && searchResultsByCategory?.length">

                <div
                    v-for="res in searchResultsByCategory"
                    :key="res.category.id"
                    class="card mb-2 shadow-sm rounded-3 border-light-subtle"
                    style="cursor:pointer;"
                    @click="selectCategory(res.category)"
                >
                    <div class="card-body">

                        <div class="fw-bold mb-2">
                            {{ res.category.name }}
                            <span class="badge bg-primary ms-2">{{ res.matches.length }}</span>
                        </div>

                        <div class="small text-muted">
                            {{ res.matches.join(', ') }}
                        </div>

                    </div>
                </div>

            </div>

            <!-- NO RESULTS -->
            <p
                v-if="globalSearch.trim() && searchResultsByCategory?.length === 0"
                class="alert alert-light text-muted fw-bold"
            >
                Ничего не найдено
            </p>


            <template v-if="!globalSearch.trim()">
                <div class="row row-cols-2">

                    <div
                        v-for="item in issues"
                        :key="item.id"
                        class="col p-1"
                    >
                        <div
                            class="card border-light-subtle shadow-sm rounded-4"
                            style="min-height:120px; cursor:pointer;"
                            @click="selectCategory(item)"
                        >
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-1">

                                <div
                                    style="font-size:36px;"
                                    v-html="item.icon"
                                ></div>

                                <div
                                    class="text-center fw-bold"
                                    style="line-height:120%; font-size:12px;"
                                >
                                    {{ item.name }}
                                </div>

                                <span
                                    v-if="(problems[item.id]?.length || 0) > 0"
                                    class="badge bg-primary rounded-3"
                                    style="position:absolute; top:10px; right:10px;"
                                >
                            {{ problems[item.id].length }}
                        </span>

                            </div>
                        </div>
                    </div>

                    <!-- OTHER -->
                    <div class="col p-1">

                        <div
                            class="card border-primary shadow-sm rounded-4"
                            style="min-height:120px; cursor:pointer;"
                            @click="selectCategory('other')"
                        >
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-1">

                                <div style="font-size:36px;">
                                    <i class="fa-solid fa-circle-question text-primary"></i>
                                </div>

                                <div
                                    class="text-center fw-bold text-primary"
                                    style="line-height:120%; font-size:12px;"
                                >
                                    Другое
                                </div>

                                <span
                                    v-if="(problems[0]?.length || 0) > 0"
                                    class="badge bg-primary rounded-3"
                                    style="position:absolute; top:10px; right:10px;"
                                >
                            {{ problems[0].length }}
                        </span>

                            </div>
                        </div>

                    </div>

                </div>
            </template>


        </template>

        <!-- DETAILS -->
        <template v-else>

            <!-- HEADER -->
            <div class="d-flex align-items-center mb-3">

                <button
                    type="button"
                    class="btn btn-light me-2"
                    @click="selected = null"
                >
                    <i class="fa fa-arrow-left"></i>
                </button>

                <h5 class="mb-0 fw-bold">
                    {{ selected === 'other' ? 'Другое' : selected.name }}
                </h5>

            </div>

            <!-- OTHER -->
            <template v-if="selected === 'other'">

                <p
                    v-if="(problems[0] || []).length === 0"
                    class="alert alert-light text-primary fw-bold"
                >
                    Вы еще ничего не добавили
                </p>

                <template
                    v-for="(item, index) in (problems[0] || [])"
                    :key="index"
                >

                    <div class="input-group mb-2">

                        <div class="form-floating">

                            <input
                                type="text"
                                class="form-control"
                                v-model="problems[0][index]"
                                :id="'custom-problem-'+index"
                                placeholder="Введите текст"
                            >

                            <label :for="'custom-problem-'+index">
                                Проблема #{{ index + 1 }}
                            </label>

                        </div>

                        <button
                            type="button"
                            class="btn btn-outline-light text-danger border-light-subtle rounded-end-4"
                            @click="removeIssue(index)"
                        >
                            <i class="fa fa-trash"></i>
                        </button>

                    </div>

                </template>

                <button
                    type="button"
                    class="btn btn-outline-primary w-100 p-2 rounded-4"
                    :disabled="(problems[0] || []).length >= 10"
                    @click="addAnotherProblem"
                >
                    Добавить еще
                </button>

            </template>

            <!-- VARIANTS -->
            <template v-else>

                <!-- SEARCH INPUT -->
                <div class="form-floating mb-3">
                    <input
                        type="text"
                        class="form-control"
                        id="searchInput"
                        placeholder="Поиск"
                        v-model="search"
                    >
                    <label for="searchInput">Поиск по проблемам</label>
                </div>

                <!-- LIST -->
                <div class="list-group shadow-sm rounded-3 overflow-hidden">

                    <button
                        type="button"
                        v-for="item in filteredVariants"
                        :key="item"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 fw-bold"
                        @click="toggleIssue(item)"
                    >
                        <span>{{ item }}</span>

                        <i
                            v-if="(problems[selected.id] || []).includes(item)"
                            class="fa fa-check text-primary"
                        ></i>
                    </button>

                    <p
                        v-if="filteredVariants.length === 0"
                        class="list-group-item text-center text-muted py-3"
                    >
                        Ничего не найдено
                    </p>

                </div>

                <button
                    type="button"
                    class="btn btn-outline-danger w-100 mt-2 p-2 rounded-4"
                    v-if="(problems[selected.id]?.length || 0) > 0"
                    @click="clearSelected"
                >
                    Очистить
                </button>

            </template>


            <!--        &lt;!&ndash; APPLY &ndash;&gt;
                    <button
                        type="button"
                        class="btn btn-primary w-100 mt-3 p-3"
                        @click="selected = null"
                    >
                        Применить
                    </button>-->

        </template>
    </div>


</template>

<script>
export default {
    name: "IssueSelector",

    props: {
        issues: {
            type: Array,
            required: true
        },

        modelValue: {
            type: Object,
            default: () => ({})
        }
    },

    emits: ['update:modelValue'],

    data() {
        return {
            selected: null,
            problems: {},
            globalSearch: "",
            search: ""

        }
    },

    computed: {
        searchResultsByCategory() {
            const q = this.globalSearch.trim().toLowerCase()
            if (!q) return null

            const results = []

            for (const cat of this.issues) {
                const variants = typeof cat.variants === "string"
                    ? JSON.parse(cat.variants || "[]")
                    : (cat.variants || [])

                const matched = variants.filter(v =>
                    v.toLowerCase().includes(q)
                )

                if (matched.length > 0) {
                    results.push({
                        category: cat,
                        matches: matched
                    })
                }
            }

            return results
        },
        filteredVariants() {
            const q = this.search.trim().toLowerCase()

            if (!q) return this.parsedVariants

            return this.parsedVariants.filter(v =>
                v.toLowerCase().includes(q)
            )
        },
        parsedVariants() {
            if (!this.selected?.variants) {
                return []
            }

            try {

                return typeof this.selected.variants === 'string'
                    ? JSON.parse(this.selected.variants)
                    : this.selected.variants

            } catch (e) {

                return []

            }

        }

    },

    watch: {

        modelValue: {
            immediate: true,
            deep: true,

            handler(val) {
                this.problems = JSON.parse(JSON.stringify(val || {}))
            }
        },

        problems: {
            deep: true,

            handler(val) {
                this.$emit('update:modelValue', val)
            }
        }

    },

    methods: {

        selectCategory(item) {
            this.selected = item
        },

        toggleIssue(item) {

            const key = this.selected.id

            if (!this.problems[key]) {
                this.problems[key] = []
            }

            const index = this.problems[key].indexOf(item)

            if (index !== -1) {

                this.problems[key].splice(index, 1)

            } else {

                this.problems[key].push(item)

            }

            if (this.problems[key].length === 0) {
                delete this.problems[key]
            }

        },

        clearSelected() {

            if (!this.selected?.id) {
                return
            }

            delete this.problems[this.selected.id]

        },

        addAnotherProblem() {

            if (!this.problems[0]) {

                this.problems[0] = [""]

            } else {

                this.problems[0].push("")

            }

        },

        removeIssue(index) {

            this.problems[0].splice(index, 1)

            if (this.problems[0].length === 0) {
                delete this.problems[0]
            }

        }

    }

}
</script>
