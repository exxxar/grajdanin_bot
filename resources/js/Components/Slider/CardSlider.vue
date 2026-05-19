<template>
    <div class="slider-wrapper">
        <button class="nav prev" @click="prev">‹</button>

        <div class="slider-window">
            <div
                class="slider-track"
                :style="trackStyle"
                @transitionend="onTransitionEnd"
            >
                <div
                    v-for="(card, index) in loopedItems"
                    :key="index"
                    class="card"
                >
                    <slot name="card" :card="card" :index="index">
                        <div class="card-body">
                            {{ card.title }}
                        </div>
                    </slot>
                </div>
            </div>
        </div>

        <button class="nav next" @click="next">›</button>
    </div>
</template>

<script>
export default {
    name: 'CardSlider',

    props: {
        items: { type: Array, required: true },
        visibleCount: { type: Number, default: 2.5 }
    },

    data() {
        return {
            currentIndex: 0,
            transitioning: false
        }
    },

    computed: {
        // Клонируем элементы: [last, ...items..., first]
        loopedItems() {
            if (!this.items.length) return []
            return [
                this.items[this.items.length - 1],
                ...this.items,
                this.items[0]
            ]
        },

        cardWidthPercent() {
            return 100 / this.visibleCount
        },

        trackStyle() {
            const translate = -(this.currentIndex * this.cardWidthPercent)
            return {
                width: `${this.loopedItems.length * this.cardWidthPercent}%`,
                transform: `translateX(${translate}%)`,
                transition: this.transitioning ? 'transform 0.3s ease' : 'none'
            }
        }
    },

    mounted() {
        // Начальная позиция — 1 (первый реальный элемент)
        this.currentIndex = 1
    },

    methods: {
        next() {
            this.transitioning = true
            this.currentIndex++
        },

        prev() {
            this.transitioning = true
            this.currentIndex--
        },

        onTransitionEnd() {
            const lastIndex = this.items.length

            // Если ушли вправо за последний элемент
            if (this.currentIndex === lastIndex + 1) {
                this.transitioning = false
                this.currentIndex = 1
            }

            // Если ушли влево за первый элемент
            if (this.currentIndex === 0) {
                this.transitioning = false
                this.currentIndex = lastIndex
            }
        }
    }
}
</script>

<style scoped>
.slider-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}

.slider-window {
    overflow: hidden;
    width: 100%;
}

.slider-track {
    display: flex;
}

.card {
    flex: 0 0 auto;
    width: calc(100% / 2.5);
    padding: 4px;
    box-sizing: border-box;
}

.card-inner {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 12px;
    background: #fff;
}

.nav {
    border: none;
    background: #eee;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    cursor: pointer;
}

.card-img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
