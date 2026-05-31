<template>
    <div class="row g-2">

        <div
            v-for="n in count"
            :key="n"
            class="col-md-4"
        >
            <div class="card h-100 rounded-4">
                <div class="card-body text-center px-2 py-3">

                    <!-- Иконка -->
                    <div
                        class="placeholder-glow mb-3 mx-auto"
                        style="width:48px;height:48px;"
                    >
                    <span
                        class="placeholder rounded-circle w-100 h-100"
                    ></span>
                    </div>

                    <!-- Заголовок -->
                    <div class="placeholder-glow mb-2">
                    <span
                        class="placeholder col-8"
                    ></span>
                    </div>

                    <div class="placeholder-glow mb-1">
                    <span
                        class="placeholder col-10"
                    ></span>
                    </div>

                    <div class="placeholder-glow">
                    <span
                        class="placeholder col-7"
                    ></span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script>
export default {
    name: "MenuCardsSkeleton",

    props: {
        count: {
            type: Number,
            default: 3
        }
    }
}
</script>
<style scoped>
.placeholder {
    position: relative;
    overflow: hidden;
}

.placeholder::after {
    content: "";
    position: absolute;
    inset: 0;
    transform: translateX(-100%);
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,.4),
        transparent
    );
    animation: shimmer 1.4s infinite;
}

@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}
</style>
