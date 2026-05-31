<template>

    <div class="row g-1">

        <div
            v-for="section in sections"
            :key="section.id"
            class="col-md-6"
        >

            <div
                class="card h-100"
                :class="{
                'border-primary':
                current===section.id
            }"
                @click="$emit('select', section.id)"
            >

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                    <span style="line-height:100%;">
                        <i
                            :class="section.icon"
                            class="me-2"
                        ></i>

                        {{ section.title }}
                    </span>

                        <span class="badge bg-primary">
                        {{ getProgress(section.id) }}%
                    </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>

<script>
export default {

    props: {
        sections: Array,
        current: String,
        getProgress: Function,
        disabled: Boolean
    },

    emits: ['select']

}
</script>
