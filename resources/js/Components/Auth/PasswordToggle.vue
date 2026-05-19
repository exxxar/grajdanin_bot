<template>
    <div class="position-relative">

        <div class="form-floating">
            <input
                :type="visible ? 'text' : 'password'"
                class="form-control"
                :id="id"
                :placeholder="placeholder"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                required
            />
            <label :for="id">{{ label }}</label>
        </div>





        <button
            type="button"
            class="btn btn-link password-toggle"
            @click="visible = !visible"
            tabindex="-1"
        >
            <i :class="visible ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
        </button>
    </div>
</template>

<script>
export default {
    name: 'PasswordToggle',

    props: {
        modelValue: String,
        id: String,
        label: String,
        placeholder: String
    },

    data() {
        return {
            visible: false
        }
    }
}
</script>

<style scoped>
.password-toggle {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1.1rem;
    padding: 0;
}
.password-toggle:hover {
    color: #000;
}
</style>
