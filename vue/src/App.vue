<template>
    <v-app>
        <LoadingScreen v-if="backend.initialLoading" />
        <component v-else :is="ux.layout">
            <v-main style="margin-top: 60px">
                <router-view />
            </v-main>
        </component>
    </v-app>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'
import { useUxStore } from '@/store/ux'
import LoadingScreen from '@/components/LoadingScreen.vue'

const backend = useBackendStore()
const ux = useUxStore()
backend.init()
console.log(import.meta.env)
console.log('process', window.process?.env)
</script>

<style lang="scss">
// global styles
html {
    overflow-y: auto !important;
}
a {
    color: inherit;
    text-decoration: none;
}
</style>
