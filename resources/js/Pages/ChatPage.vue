<script setup>
import Chat from "@/Components/Chat/Chat.vue";
</script>
<template>
    <Chat :report-id="reportId"></Chat>
</template>
<script>
export default {
    computed: {
        reportId() {
            const raw = this.$route.query.report
            if (!raw) return null
            const id = Number(raw)
            return Number.isFinite(id) ? id : null
        },
    },
}
</script>
