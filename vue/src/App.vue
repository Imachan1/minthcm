<template>
    <v-app>
        <MintOverlay />
        <MintPopups />
        <LoadingScreen v-if="backend.initialLoading" />
        <component v-else :is="ux.layout">
            <v-main
                class="mint-content"
                :style="{
                    marginRight: ux.drawer && $vuetify.display.xlAndUp ? 'var(--v-drawer-width)' : '0px',
                }"
            >
                <router-view :key="$route.fullPath" />
            </v-main>
        </component>
    </v-app>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useUxStore } from '@/store/ux'
import MintPopups from '@/components/MintPopups/MintPopups.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'
import MintOverlay from './components/MintOverlay.vue'
import '/node_modules/flag-icons/css/flag-icons.min.css'

const backend = useBackendStore()
const ux = useUxStore()
const router = useRouter()

onMounted(async () => {
    await backend.init()
    console.log('mounted', router.resolve('#/modules/Calls'))
})
</script>

<style scoped lang="scss">
.mint-content {
    margin-top: var(--v-top-nav-height);
    margin-right: var(--v-drawer-width);
}
</style>
