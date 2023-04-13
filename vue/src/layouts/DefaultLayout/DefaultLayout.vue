<template>
    <DefaultLayoutNavbar />
    <DefaultLayoutSidebar />
    <slot></slot>
</template>

<script setup lang="ts">
import DefaultLayoutNavbar from './DefaultLayoutNavbar.vue'
import DefaultLayoutSidebar from './DefaultLayoutSidebar.vue'
</script>

<style scoped lang="scss"></style>
