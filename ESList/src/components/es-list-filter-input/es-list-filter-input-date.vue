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
                v-model="input.value"
                dense
                outlined
                :label="input.label"
                prepend-inner-icon="mdi-calendar"
                v-bind="attrs"
                v-on="on"
                autocomplete="off"
                :error="!isValidDate"
                hide-details
            />
        </template>
        <v-date-picker
            v-model="input.value"
            no-title
            @input="menu = false"
            locale="pl"
            first-day-of-week="1"
            show-week
            locale-first-day-of-year="4"
            color="rgba(0, 0, 0, 0.4)"

        />
    </v-menu>
</template>

<script>
export default {
    props: {
        input: { type: Object }
    },
    data: () => ({
        menu: false
    }),
    computed: {
        isValidDate() {
            return !this.input.value || this.input.value.length === 10
        }
    }
}
</script>

<style>

</style>
