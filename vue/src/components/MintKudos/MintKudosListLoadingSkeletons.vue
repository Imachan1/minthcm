<template>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
</template>

<script setup lang="ts">
import { VSkeletonLoader } from 'vuetify/labs/VSkeletonLoader'
</script>

<!-- zerknąć na margin, bo po zmianie kolejności w MintKudosList zniknął -->

<style scoped lang="scss">
.kudos-skeleton {
    width: 400px;
    margin: 0 auto;
}
.kudos-skeleton:first-child {
    margin-top: 16px;
}
</style>
