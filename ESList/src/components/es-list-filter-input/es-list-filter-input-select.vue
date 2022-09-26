<template>
    <v-select
        :items="['item1', 'item2', 'item3']"
        dense
        :label="label(input.label)"
        outlined
        hide-details
    />    
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        input: { type: Object }
    },
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    }
}
</script>

<style>

</style>
