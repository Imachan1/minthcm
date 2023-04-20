<template>
    <v-text-field
        v-model="val"
        @input="handleInput"
        @keyup.enter="handleKeyEnter"
        dense
        outlined
        hide-details
        :label="input.label"
    />
</template>

<script>
export default {
    props: {
        input: { type: Object },
    },
    data() {
        return {
            val: this.input.value,
            debounceTimeout: null,
        }
    },
    methods: {
        handleKeyEnter() {
            clearTimeout(this.debounceTimeout)
            this.input.value = this.val
        },
        handleInput() {
            clearTimeout(this.debounceTimeout)
            this.debounceTimeout = setTimeout(() => {
                this.input.value = this.val
            }, 1000)
        }
    }
}
</script>

<style>

</style>
