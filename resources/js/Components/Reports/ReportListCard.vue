<template>
    <div class="card shadow-sm border-0 mb-3 rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start gap-3">

                <!-- Левая часть -->
                <div class="flex-grow-1">
                    <h6 class="mb-1">Заявка №{{ item.report_id }} (<span class="small text-secondary">{{ item.report.phone }}</span>)</h6>

                    <p class="mb-1 small text-muted">
                        {{ item.received_from || 'Без имени' }}
                        <span v-if="item.report?.phone"> </span>
                    </p>

                    <p class="mb-0 small fst-italic">
                        {{ truncate(item.problem_description, 120) }}
                    </p>

                    <small class="text-muted" v-if="item.created_at">
                        {{ formatDate(item.created_at) }}
                    </small>
                </div>

                <!-- Правая часть -->
                <div class="d-flex flex-column">
                    <button
                        type="button"
                        class="btn btn-primary btn-sm text-nowrap mb-2"
                        @click="openChat(item)"
                    >
                        <i class="fa-regular fa-comments"></i>


                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm text-nowrap"
                        @click="openDetails(item)"
                    >
                        <i class="fa-regular fa-file-lines"></i>



                    </button>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'ReportListCard',

    props: {
        item: { type: Object, required: true }
    },

    methods: {
        truncate(text, length) {
            if (!text) return ''
            return text.length > length ? text.slice(0, length) + '…' : text
        },

        formatDate(dateStr) {
            const d = new Date(dateStr)
            return d.toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })
        },

        openChat(report) {
            this.$emit('open-chat', report)
        },

        openDetails(report) {
            this.$emit('open-details', report)
        }
    }
}
</script>
