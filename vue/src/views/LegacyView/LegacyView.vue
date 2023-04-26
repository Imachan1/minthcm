<template>
    <iframe
        class="legacy-view"
        :src="legacyUrl"
        @load="handleLegacyUrlChange"
    />
</template>

<script setup lang="ts">
import { onMounted, onBeforeUnmount, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useUrlStore } from '@/store/url'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const url = useUrlStore()

onMounted(() => {
    // messages from iframe
    window.addEventListener('message', handleMessageEvent)
})

onBeforeUnmount(() => {
    window.removeEventListener('message', handleMessageEvent)
})

function handleMessageEvent(e: MessageEvent) {
    if (!e.data || typeof e.data !== 'string' || e.data.slice(0, 4) !== 'http') {
        return
    }
    const path = url.fromLegacyUrl(e.data)
    if (route.path === path) {
        return
    }
    router.push({
        path,
        force: true,
    })
}

function handleLegacyUrlChange(e: Event) {
    const href = (e.target as HTMLIFrameElement).contentWindow?.location.href
    if (!href) {
        return console.error('Legacy View error: url empty')
    }
    const path = url.fromLegacyUrl(href)
    console.log(route.path === path, 'old', route.path, 'new', path)
    if (path === route.path) {
        return
    }
    const routeName = router.resolve(path)?.name?.toString() ?? ''
    if (routeName === 'login') {
        auth.user = null
    }
    if (!['legacy', 'dashboard', 'administration'].includes(routeName)) {
        router.push(path)
    } else {
        // router.push(path) /* problem: podwójny reload legacy => legacy */
        // history.replaceState(
        //     {},
        //     '',
        //     `/minthcm${path}`,
        // ) /* problem: powrót do pierwotnego widoku legacy */
    }
}

const legacyUrl = computed(() => {
    console.log(url.toLegacyUrl(location.href))
    return url.toLegacyUrl(location.href)
})
</script>

<style scoped lang="scss">
.legacy-view {
    width: 100%;
    height: calc(100vh - 67px);
    border: none;
}
</style>
