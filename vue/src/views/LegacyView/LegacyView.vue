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

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

onMounted(() => {
    // messages from iframe
    window.addEventListener('message', handleMessageEvent)
})

onBeforeUnmount(() => {
    window.removeEventListener('message', handleMessageEvent)
})

function handleMessageEvent(e: MessageEvent) {
    console.log('message', e.data)
    const urlObj = new URL(e.data)
    const module = urlObj.searchParams.get('module')
    const action = urlObj.searchParams.get('action')
    const record = urlObj.searchParams.get('record')
    let path = '/'
    if (module) {
        path += module
        if (action) {
            path += `/${action}`
            if (record) {
                path += `/${record}`
            }
        }
    }
    console.log(route.path === path, 'old', route.path, 'new', path)
    if (route.path === path) {
        return
    }
    router.push(path)
}

function handleLegacyUrlChange(e: Event) {
    const url = (e.target as HTMLIFrameElement).contentWindow?.location.href
    console.log('legacy url', url)
    if (!url) {
        return console.error('Legacy View error: url empty')
    }
    const urlObj = new URL(url)
    const module = urlObj.searchParams.get('module')
    const action = urlObj.searchParams.get('action')
    const record = urlObj.searchParams.get('record')
    if (action === 'ajaxui') {
        return console.error('Legacy View error: ajaxui action')
    }
    let path = '/'
    if (module) {
        path += module
        if (action) {
            path += `/${action}`
            if (record) {
                path += `/${record}`
            }
        }
    }
    console.log(route.path === path, 'old', route.path, 'new', path)
    if (path === route.path) {
        return
    }
    const routeName = router.resolve(path)?.name?.toString() ?? ''
    if (routeName === 'login') {
        auth.user = null
    }
    if (!['legacy', 'dashboard'].includes(routeName)) {
        router.push(path)
    } else {
        // router.push(path) /* problem: podwójny reload legacy => legacy */
        history.replaceState(
            {},
            '',
            path,
        ) /* problem: powrót do pierwotnego widoku legacy */
    }
}

const legacyUrl = computed(() => {
    const base = import.meta.env.BASE_URL + 'index.php'
    const url = new URL(location.href)
    const path = route.fullPath.split('/')
    const module = path[1]
    const action = path[2]
    const record = path[3]
    const legacyUrl = new URL(url.origin + base)
    if (module) {
        legacyUrl.searchParams.set('module', module)
    }
    if (action) {
        legacyUrl.searchParams.set('action', action)
    }
    if (record) {
        legacyUrl.searchParams.set('record', record)
    }
    return legacyUrl.href
})
</script>

<style scoped lang="scss">
.legacy-view {
    width: 100%;
    height: calc(100vh - 67px);
    border: none;
}
</style>
