<template>
    <h5 class="card-title mb-3">
        Отчёт №{{ item.id }}
    </h5>

    <div class="mb-2">
        <strong>Получено от:</strong>
        <div>{{ item.received_from }}</div>
    </div>

    <div class="mb-2">
        <strong>Описание проблемы:</strong>
        <div>{{ item.problem_description }}</div>
    </div>

    <div class="mb-2">
        <strong>Комментарий:</strong>
        <div>{{ item.comment || '—' }}</div>
    </div>

    <!-- JSON массивы -->
    <div v-if="parsed.help_formats.length" class="mb-2">
        <strong>Форматы помощи:</strong>
        <ul class="mb-0">
            <li v-for="(v, i) in parsed.help_formats" :key="i">{{ v }}</li>
        </ul>
    </div>

    <div v-if="parsed.problems.length" class="mb-2">
        <strong>Проблемы:</strong>
        <ul class="mb-0">
            <li v-for="(v, i) in parsed.problems" :key="i">{{ v }}</li>
        </ul>
    </div>

    <div v-if="parsed.solutions.length" class="mb-2">
        <strong>Предложенные решения:</strong>
        <ul class="mb-0">
            <li v-for="(v, i) in parsed.solutions" :key="i">{{ v }}</li>
        </ul>
    </div>

    <div v-if="parsed.difficulties.length" class="mb-2">
        <strong>Трудности:</strong>
        <ul class="mb-0">
            <li v-for="(v, i) in parsed.difficulties" :key="i">{{ v }}</li>
        </ul>
    </div>

    <div v-if="parsed.audio_files.length" class="mb-2">
        <strong>Аудиофайлы:</strong>
        <ul class="mb-0">
            <li v-for="(v, i) in parsed.audio_files" :key="i">
                <a :href="v" target="_blank">Аудио {{ i + 1 }}</a>
            </li>
        </ul>
    </div>

    <hr>

    <!-- Вложенный объект report -->
    <h6 class="mb-2">Информация по обращению</h6>

    <div class="mb-1"><strong>ID обращения:</strong> {{ item.report.id }}</div>
    <div class="mb-1"><strong>Тип:</strong> {{ item.report.type }}</div>
    <div class="mb-1"><strong>Приоритет:</strong> {{ item.report.priority }}</div>
    <div class="mb-1"><strong>Телефон:</strong> {{ item.report.phone }}</div>
    <div class="mb-1"><strong>Получено:</strong> {{ item.report.received_at }}</div>


</template>

<script>
export default {
    name: 'ReportCard',

    props: {
        item: { type: Object, required: true }
    },

    computed: {
        parsed() {
            return {
                help_formats: this.safeParse(this.item.help_formats),
                problems: this.safeParse(this.item.problems),
                solutions: this.safeParse(this.item.solutions),
                difficulties: this.safeParse(this.item.difficulties),
                audio_files: this.safeParse(this.item.audio_files),
            }
        }
    },

    methods: {
        safeParse(value) {
            try {
                return JSON.parse(value || '[]')
            } catch {
                return []
            }
        }
    }
}
</script>
