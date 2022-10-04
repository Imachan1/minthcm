<template>
    <v-select
        v-model="input.value"
        class="es-list-filter-input-multiselect"
        :items="getList(fieldDefs.options)"
        dense
        style="width: fit-content"
        :label="label(input.label)"
        multiple
        small-chips
        deletable-chips
        outlined
        hide-details
    />
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        input: { type: Object, required: true },
        fieldDefs: { type: Object },
    },
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
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
.es-list-filter-input-multiselect {
    .v-select__selections input {
        display: none;
    }
    .v-chip--select {
        margin: 4px !important;
    }
}
</style>
