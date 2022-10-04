<template>
    <v-text-field
        v-model="input.value"
        dense
        outlined
        hide-details
        :label="$store.getters['getLabel'](input.label)"
    />
</template>

<script>
export default {
    props: {
        input: { type: Object },
    },
}
</script>

<style>

</style>
