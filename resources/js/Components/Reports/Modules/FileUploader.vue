<template>
    <div class="mb-3">
        <label class="form-label fw-bold">
            Прикрепить документы (до 10 файлов)
        </label>

        <input
            type="file"
            class="form-control"
            multiple
            @change="handleFiles"
        />

        <small class="text-muted">
            Можно выбрать до 10 файлов любого формата
        </small>

        <!-- Список файлов -->
        <ul
            v-if="files.length"
            class="list-group mt-2"
        >
            <li
                v-for="(file, index) in files"
                :key="index"
                class="list-group-item d-flex justify-content-between align-items-center"
            >
                <span class="text-truncate">
                    <i class="fa-solid fa-paperclip me-2"></i>
                    {{ file.name }}
                </span>

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm"
                    @click="removeFile(index)"
                >
                    ×
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'FileUploader',

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

    computed: {
        files() {
            return this.modelValue
        }
    },

    methods: {
        handleFiles(event) {
            const selectedFiles = Array.from(event.target.files)

            const mergedFiles = [
                ...this.files,
                ...selectedFiles
            ].slice(0, this.maxFiles)

            this.$emit('update:modelValue', mergedFiles)

            // сброс input, чтобы можно было выбрать тот же файл повторно
            event.target.value = ''
        },

        removeFile(index) {
            const updated = [...this.files]
            updated.splice(index, 1)

            this.$emit('update:modelValue', updated)
        }
    }
}
</script>
