<template>
    <v-select
        v-model="input.value"
        class="es-list-filter-input-select"
        :items="getList(fieldDefs.options)"
        dense
        style="width: fit-content"
        :label="input.label"
        outlined
        hide-details
    />
</template>

<script>
export default {
    props: {
        input: { type: Object, required: true },
        fieldDefs: { type: Object },
    },
    methods: {
        getList(list) {
            return Object.entries(
                SUGAR.language.languages['app_list_strings'][list] ?? {}
            ).map(([value, text]) => ({ value, text }))
        },
    }
}
</script>

<style lang="scss">

</style>
