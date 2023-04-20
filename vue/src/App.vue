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
</script>
