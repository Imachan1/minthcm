<template>
    <v-data-table-server
        :headers="[{title:'name',key:'name'},{title:'date',key:'date'}]"
        :items="[{name:'test',date:'17.01.2023'}]"
    />
</template>

<script setup lang="ts">
import { VDataTableServer } from 'vuetify/labs/VDataTable'
</script>

<style scoped lang="scss"></style>
