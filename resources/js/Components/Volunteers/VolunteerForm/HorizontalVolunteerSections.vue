<template>

    <div class="card mb-2" style="position:sticky; top:80px; z-index:100;">
        <div class="card-body p-2">
            <div class="d-flex align-items-start gap-1">


                <div class="p-1 flex-shrink-0">
                    <button
                        type="button"
                        style="font-size:10px;"
                        class="btn btn-primary "
                        @click="$emit('select','sections')"
                    >
                        К секциям
                    </button>
                </div>




                <!-- ГОРИЗОНТАЛЬНЫЕ КНОПКИ -->
                <div
                    class="d-flex gap-1 overflow-auto flex-nowrap w-100 py-1"
                >

                    <button
                        v-for="section in sections"
                        :key="section.id"
                        type="button"
                        :id="section.id"
                        style="font-size:10px;"
                        class="btn flex-shrink-0"
                        :class="current === section.id
                    ? 'btn-primary'
                    : 'btn-outline-primary'"
                        @click="$emit('select', section.id)"
                    >

                        <i
                            v-if="section.icon"
                            :class="section.icon"
                            class="me-1"
                        ></i>

                        {{ section.title }}

                    </button>

                </div>

            </div>
        </div>
    </div>


</template>

<script>
export default {

    props: {
        sections: {
            type: Array,
            required: true
        },
        current: {
            type: String,
            default: null
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },

    emits: ['select']

}
</script>
<style scoped>
::-webkit-scrollbar {
    width: 3px;
    height: 3px;

}
</style>
