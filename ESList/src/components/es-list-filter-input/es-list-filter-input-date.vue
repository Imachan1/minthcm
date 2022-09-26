<template>
    <v-menu
        v-model="menu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="date"
                dense
                outlined
                style="max-width:200px"
                :label="label(input.label)"
                prepend-inner-icon="mdi-calendar"
                v-bind="attrs"
                v-on="on"
                autocomplete="off"
                hide-details
            />
        </template>
        <v-date-picker
            v-model="date"
            no-title
            @input="menu = false"
            locale="pl"
            first-day-of-week="1"
            show-week
            locale-first-day-of-year="4"
        />
    </v-menu>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        input: { type: Object }
    },
    data: () => ({
        date: null,
        menu: false
    }),
    computed: {
        ...mapGetters({
            label: 'getLabel'
        })
    }
}
</script>

<style>

</style>
