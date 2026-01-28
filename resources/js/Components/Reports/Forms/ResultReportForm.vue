<template>
    <form class="p-3" @submit.prevent="submitForm">

        <!-- REPORT SELECT -->
        <h5 class="mb-3">Основная информация</h5>

        <div class="form-floating mb-3">
            <select class="form-select" v-model="form.report_id">
                <option v-for="r in reports" :key="r.id" :value="r.id">
                    Отчёт #{{ r.id }}
                </option>
            </select>
            <label>Отчёт</label>
        </div>

        <!-- TOPIC -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" v-model="form.topic">
            <label>Тема</label>
        </div>

        <!-- DESCRIPTION -->
        <div class="form-floating mb-3">
            <textarea class="form-control" style="height: 120px" v-model="form.description"></textarea>
            <label>Описание</label>
        </div>

        <hr class="my-4">

        <!-- ACTIONS -->
        <h5 class="mb-2">Действия</h5>

        <div class="row g-2 mb-2">
            <template v-for="item in visibleActions" :key="item.id">
                <div class="col-4">
                    <div class="nuance-tile"
                         :class="{ active: form.actions.includes(item.id) }"
                         @click="toggleSelection('actions', item.id)">
                        <img :src="item.icon" class="tile-icon" alt="">
                        <div class="tile-title">{{ item.name }}</div>
                    </div>
                </div>
            </template>
        </div>

        <button v-if="actionsHiddenCount > 0"
                type="button"
                class="btn btn-outline-secondary btn-sm w-100 mb-3"
                @click="showMore('actions')">
            Показать ещё ({{ actionsHiddenCount }})
        </button>

        <hr class="my-4">

        <!-- RESULT -->
        <h5 class="mb-2">Результат</h5>

        <div class="row g-2 mb-2">
            <template v-for="item in visibleResult" :key="item.id">
                <div class="col-4">
                    <div class="nuance-tile"
                         :class="{ active: form.result.includes(item.id) }"
                         @click="toggleSelection('result', item.id)">
                        <img :src="item.icon" class="tile-icon" alt="">
                        <div class="tile-title">{{ item.name }}</div>
                    </div>
                </div>
            </template>
        </div>

        <button v-if="resultHiddenCount > 0"
                type="button"
                class="btn btn-outline-secondary btn-sm w-100 mb-3"
                @click="showMore('result')">
            Показать ещё ({{ resultHiddenCount }})
        </button>

        <hr class="my-4">

        <!-- DIFFICULTIES -->
        <h5 class="mb-2">Трудности</h5>

        <div class="row g-2 mb-2">
            <template v-for="item in visibleDifficulties" :key="item.id">
                <div class="col-4">
                    <div class="nuance-tile"
                         :class="{ active: form.difficulties.includes(item.id) }"
                         @click="toggleSelection('difficulties', item.id)">
                        <img :src="item.icon" class="tile-icon" alt="">
                        <div class="tile-title">{{ item.name }}</div>
                    </div>
                </div>
            </template>
        </div>

        <button v-if="difficultiesHiddenCount > 0"
                type="button"
                class="btn btn-outline-secondary btn-sm w-100 mb-3"
                @click="showMore('difficulties')">
            Показать ещё ({{ difficultiesHiddenCount }})
        </button>

        <button class="btn btn-primary w-100 btn-lg mt-3">Сохранить</button>
    </form>
</template>

<script>
export default {
    name: "ResultReportForm",

    data() {
        return {
            form: {
                report_id: "",
                topic: "",
                description: "",
                actions: [],
                result: [],
                difficulties: []
            },

            reports: [], // подгружается API

            nuances: [], // единый справочник

            limit: {
                actions: 9,
                result: 9,
                difficulties: 9
            }
        };
    },

    computed: {
        // TYPE = 1 → actions
        actionsList() {
            return this.nuances.filter(n => n.type === 1);
        },

        // TYPE = 2 → result (если появятся)
        resultList() {
            return this.nuances.filter(n => n.type === 2);
        },

        // TYPE = 3 → difficulties
        difficultiesList() {
            return this.nuances.filter(n => n.type === 3);
        },

        visibleActions() {
            return this.actionsList.slice(0, this.limit.actions);
        },
        visibleResult() {
            return this.resultList.slice(0, this.limit.result);
        },
        visibleDifficulties() {
            return this.difficultiesList.slice(0, this.limit.difficulties);
        },

        actionsHiddenCount() {
            return Math.max(0, this.actionsList.length - this.limit.actions);
        },
        resultHiddenCount() {
            return Math.max(0, this.resultList.length - this.limit.result);
        },
        difficultiesHiddenCount() {
            return Math.max(0, this.difficultiesList.length - this.limit.difficulties);
        }
    },

    methods: {
        toggleSelection(field, id) {
            const arr = this.form[field];
            const index = arr.indexOf(id);

            if (index === -1) arr.push(id);
            else arr.splice(index, 1);
        },

        showMore(field) {
            this.limit[field] += 9;
        },

        async loadNuances() {
            const res = await axios.get('/api/issue-categories');

            this.nuances = res.data.map(item => ({
                ...item,
                variants: item.variants ? JSON.parse(item.variants) : [],
                icon: `/icons/${item.icon}.svg`
            }));
        },

        submitForm() {
            console.log("FORM:", this.form);
        }
    },

    mounted() {
        this.loadNuances();
    }
};
</script>

<style scoped>
.nuance-tile {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 12px 6px;
    text-align: center;
    background: #fff;
    cursor: pointer;
    transition: 0.2s;
}

.nuance-tile.active {
    border-color: #0d6efd;
    background: #e7f1ff;
}

.tile-icon {
    width: 32px;
    height: 32px;
    object-fit: contain;
    margin-bottom: 6px;
}

.tile-title {
    font-size: 13px;
    line-height: 1.2;
}
</style>
