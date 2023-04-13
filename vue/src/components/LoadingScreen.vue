<template>
    <div class="loading-screen">
        <h1 v-text="'Loading...'" />
    </div>
</template>

<script setup lang="ts">
import { useUxStore } from '@/store/ux'

const ux = useUxStore()
</script>

<style scoped lang="scss">
.loading-screen {
    z-index: 10000;
    position: fixed;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000d;
    color: white;
}
</style>
