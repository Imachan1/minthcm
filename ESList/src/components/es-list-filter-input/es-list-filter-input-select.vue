<template>
    <v-col cols="auto" class="pa-0">
        <v-select
            v-model="value"
            class="es-list-filter-input-select"
            :items="getList(fieldDefs.options)"
            dense
            style="width: fit-content"
            :label="label(input.label)"
            :multiple="input.options?.multi"
            :small-chips="input.options?.multi"
            deletable-chips
            outlined
            hide-details
        />
    </v-col>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        input: { type: Object },
        fieldDefs: { type: Object },
    },
    data: () => ({
        value: null
    }),
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
.es-list-filter-input-select {
    .v-select__selections input {
        display: none;
    }
    &.v-select {
        min-width: 200px;
    }
}
</style>
