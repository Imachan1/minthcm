<template>
    <v-app>
        <MintOverlay />
        <MintPopups />
        <LoadingScreen v-if="backend.initialLoading" />
        <component v-else :is="ux.layout">
            <v-main style="margin-top: var(--v-top-nav-height)">
                <router-view :key="$route.fullPath" />
            </v-main>
        </component>
    </v-app>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'
import { useUxStore } from '@/store/ux'
import MintPopups from '@/components/MintPopups.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'
import MintOverlay from './components/MintOverlay.vue'
import '/node_modules/flag-icons/css/flag-icons.min.css'

const backend = useBackendStore()
const ux = useUxStore()
backend.init()
</script>
