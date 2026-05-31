<script setup>
import IncomingReportForm from "@/Components/Reports/Forms/IncomingReportForm.vue";
</script>
<template>
    <div class="container p-2">
        <IncomingReportForm @success="onIncomingSuccess"></IncomingReportForm>
    </div>
</template>
