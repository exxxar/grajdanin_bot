<template>
    <div class="mb-3">
        <label class="form-label fw-bold small">
            Голосовые сообщения  <span v-if="audios.length>0">{{audios.length}}/{{maxFiles}}</span>
        </label>

        <div class="btn-group mb-2 w-100" role="group">
            <button
                type="button"
                class="btn btn-outline-primary p-2 rounded-start-4"
                :disabled="isRecording || audios.length >= maxFiles"
                @click="startRecording"
            >



                <template v-if="isRecording">
                    <div class="voice-bars w-100 d-flex justify-content-center align-items-center">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                </template>

                <template v-else>

                    <p class="d-flex align-items-center mb-0 justify-content-center">
                        <i class="fa-solid fa-microphone"></i>
                        Записать
                    </p>
                </template>
            </button>

            <button
                type="button"
                class="btn btn-outline-danger p-2 rounded-end-4"
                :disabled="!isRecording"
                @click="stopRecording"
            >
                <i class="fa-solid fa-stop"></i> Стоп
            </button>
        </div>

        <!-- Список записанных аудио -->
        <ul
            v-if="audios.length"
            class="list-group mt-2 list-group-flush"
        >
            <li
                v-for="(file, index) in audios"
                :key="index"
                class="list-group-item d-flex justify-content-between align-items-center"
            >
                <audio :src="file.url" controls></audio>

                <button
                    type="button"
                    class="btn btn-link text-danger"
                    @click="removeAudio(index)"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'AudioRecorder',

    props: {
        modelValue: {
            type: Array,
            default: () => []
        },
        maxFiles: {
            type: Number,
            default: 10
        }
    },

    data() {
        return {
            mediaRecorder: null,
            chunks: [],
            isRecording: false
        }
    },

    computed: {
        audios() {
            return this.modelValue
        }
    },

    methods: {
        async startRecording() {
            if (this.isRecording) return

            const stream = await navigator.mediaDevices.getUserMedia({ audio: true })

            this.mediaRecorder = new MediaRecorder(stream)
            this.chunks = []
            this.isRecording = true

            this.mediaRecorder.ondataavailable = (e) => {
                this.chunks.push(e.data)
            }

            this.mediaRecorder.onstop = () => {
                const blob = new Blob(this.chunks, { type: 'audio/webm' })
                const url = URL.createObjectURL(blob)

                const updated = [
                    ...this.audios,
                    { file: blob, url }
                ].slice(0, this.maxFiles)

                this.$emit('update:modelValue', updated)
            }

            this.mediaRecorder.start()
        },

        stopRecording() {
            if (!this.mediaRecorder) return

            this.mediaRecorder.stop()
            this.isRecording = false
        },

        removeAudio(index) {
            const updated = [...this.audios]
            URL.revokeObjectURL(updated[index].url)
            updated.splice(index, 1)

            this.$emit('update:modelValue', updated)
        }
    },

    beforeUnmount() {
        this.audios.forEach(a => URL.revokeObjectURL(a.url))
    }
}
</script>
<style>
.voice-bars {
    display: flex;
    align-items: flex-end;
    gap: 4px;
    height: 20px;
}

.voice-bars span {
    width: 4px;
    height: 4px;
    background: #444444;
    border-radius: 2px;
    animation: voice 0.8s infinite ease-in-out;
}
.voice-bars span:nth-child(4),
.voice-bars span:nth-child(7),
.voice-bars span:nth-child(1) {
    animation-delay: 0s;
}
.voice-bars span:nth-child(2),
.voice-bars span:nth-child(5),
.voice-bars span:nth-child(8) {
    animation-delay: 0.2s;
}

.voice-bars span:nth-child(3),
.voice-bars span:nth-child(6),
.voice-bars span:nth-child(9) {
    animation-delay: 0.4s;
}

@keyframes voice {
    0%   { height: 4px; }
    50%  { height: 20px; }
    100% { height: 4px; }
}

</style>
