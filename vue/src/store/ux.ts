import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useAuthStore } from './auth'
import DefaultLayout from '@/layouts/DefaultLayout/DefaultLayout.vue'
import GuestLayout from '@/layouts/GuestLayout/GuestLayout.vue'

export const useUxStore = defineStore('ux', () => {
    const defaultLoadingMessage = 'Loading...'
    const loadingScreen = ref('')
    const drawer = ref(false)

    function showLoadingScreen(msg?: string) {
        loadingScreen.value = msg ?? defaultLoadingMessage
    }

    function closeLoadingScreen() {
        loadingScreen.value = ''
    }

    const layout = computed(() => {
        const route = useRoute()
        if (route.meta?.layout) {
            return route.meta.layout
        }
        const auth = useAuthStore()
        return auth.user?.id ? DefaultLayout : GuestLayout
    })

    return {
        loadingScreen,
        showLoadingScreen,
        closeLoadingScreen,
        layout,
        drawer,
    }
})
