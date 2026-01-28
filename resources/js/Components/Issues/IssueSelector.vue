<template>
    <div class="row row-cols-2">
        <div class="col mb-0 mt-0" v-for="item in issues">
            <div
                data-bs-toggle="modal" data-bs-target="#choose-modal"
                @click="selectIssueCategory(item)"
                style="min-height:120px;"
                class="card mb-2 border-light-subtle shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-1">
                    <div
                        style="font-size:36px;"
                        v-html="item.icon"></div>
                    <div
                        class="text-center fw-bold"
                        style="line-height: 120%;font-size:12px; "
                        v-html="item.name"></div>


                    <span
                        v-if="(problems[item.id]?.length || 0)>0"
                        class="badge bg-warning"
                        style="position:absolute; top:0px;right:0px;font-size:14px;">
                                {{ problems[item.id]?.length || 0 }}</span>
                </div>

            </div>

        </div>

        <div class="col mb-0 mt-0">
            <div
                data-bs-toggle="modal" data-bs-target="#choose-modal"
                @click="selectIssueCategory(null)"
                style="min-height:120px;"
                class="card mb-2 border-warning shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-1">
                    <div
                        style="font-size:36px;"
                    ><i class="fa-solid fa-circle-question text-warning"></i></div>
                    <div
                        class="text-center fw-bold text-warning"
                        style="line-height: 120%;font-size:12px; ">Другое
                    </div>


                    <span
                        v-if="(problems[0]?.length || 0)>0"
                        class="badge bg-warning"
                        style="position:absolute; top:0px;right:0px;font-size:14px;">
                                {{ problems[0]?.length || 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="choose-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Окно выбора</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <template v-if="selected">
                        <ul class="list-group">
                            <li
                                @click="toggleIssue(item)"
                                v-for="item in JSON.parse(selected?.variants)"
                                class="list-group-item p-3 fw-bold d-flex justify-content-between"
                                aria-current="true">
                                <span>{{ item }}</span>
                                <span v-if="(problems[selected.id] || []).includes(item)"><i
                                    class="fa fa-check text-primary "></i></span>
                            </li>

                        </ul>

                        <button type="button"
                                @click="problems[selected.id]=[]"
                                class="btn btn-outline-light p-3 mt-2 text-primary w-100">Очистить
                        </button>

                    </template>

                    <template v-else>
                        <p
                            v-if="(problems[0]||[]).length===0"
                            class="alert alert-light text-primary mb-2 fw-bold">
                            Вы еще ничего не добавили
                        </p>
                        <template v-for="(item, index) in (problems[0]||[])">


                            <div class="input-group mb-2">

                                <div class="form-floating">
                                    <input type="text"
                                           v-model="problems[0][index]"
                                           class="form-control" :id="'issue-custom-input-'+index"
                                           placeholder="Проблема">
                                    <label :for="'issue-custom-input-'+index">Введите текст #{{ index }}</label>
                                </div>

                                <span class="input-group-text" id="basic-addon1">
                                           <button
                                               type="button"
                                               class="btn btn-link ml-2 text-danger"
                                               @click="removeIssue(index)"
                                           >
                            <i class="fas fa-trash"></i>
                        </button>
                                    </span>
                            </div>
                        </template>


                        <button type="button"
                                :disabled="(problems[0]||[]).length>=10"
                                @click="addAnotherProblem"
                                class="btn btn-outline-light p-3 mt-2 text-primary w-100">Добавить еще
                        </button>

                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button"

                            class="btn btn-outline-secondary p-3 w-100" data-bs-dismiss="modal">Продолжить
                    </button>

                </div>
            </div>
        </div>
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

    watch: {
        /*      modelValue: {
                  immediate: true,
                  deep: true,
                  handler(val) {
                      // делаем копию, чтобы не мутировать prop напрямую
                      this.problems = JSON.parse(JSON.stringify(val || {}))
                  }
              },*/
        problems: {
            deep: true,
            handler(val) {
                this.$emit('update:modelValue', val)
            }
        }
    },
    data() {
        return {

            selected: null,
            problems: []
        };
    },

    mounted() {
        if (this.modelValue)
            this.problems = JSON.parse(JSON.stringify(this.modelValue || {}))
    },
    methods: {
        selectIssueCategory(item) {
            this.selected = null
            this.$nextTick(() => {
                this.selected = item
            })
        },
        removeIssue(index) {
            this.problems[0].splice(index, 1)
        },
        addAnotherProblem() {
            if (!this.problems[0])
                this.problems[0] = []
            else
                this.problems[0].push("")
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

            // если список пуст — удаляем ключ
            if (this.problems[key].length === 0) {
                delete this.problems[key]
            }
        }
    }

}
</script>
