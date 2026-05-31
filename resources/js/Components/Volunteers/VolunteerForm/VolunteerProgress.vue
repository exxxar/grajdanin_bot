<template>

    <div class="d-flex justify-content-between mb-1">
        <span>
            Заполненность анкеты
        </span>

        <strong>
            {{ progress }}%
        </strong>
    </div>

    <div class="progress" style="height: 12px;">

        <div
            class="progress-bar progress-bar-striped progress-bar-animated"
            :class="progressClass"
            :style="{
                width: progress + '%'
            }"
        >
        </div>

    </div>

</template>

<script>
export default {
    props: {
        progress: {
            type: Number,
            default: 0
        }
    },

    computed: {
        progressClass() {

            if (this.progress < 25)
                return 'bg-danger';

            if (this.progress < 50)
                return 'bg-warning';

            return 'bg-success';
        }
    }
}
</script>
