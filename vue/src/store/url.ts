import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { defineStore } from 'pinia'

export const useUrlStore = defineStore('url', () => {
    const DEFAULT_MODULE = 'Home'
    const route = useRoute()

    const path = computed(() => {
        return route.path.split('/')
    })

    const module = computed(() => {
        return path.value?.[1] || DEFAULT_MODULE
    })

    const action = computed(() => {
        return path.value?.[2]
    })

    const record = computed(() => {
        return path.value?.[3]
    })

    function toLegacyUrl(url: string | URL) {
        const base = import.meta.env.BASE_URL + 'legacy/index.php'
        if (typeof url === 'string') {
            url = new URL(url)
        }
        const path = url.pathname.split('/')
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
        url.searchParams.forEach((value, param) => {
            legacyUrl.searchParams.set(param, value)
        })
        return legacyUrl.href
    }

    function fromLegacyUrl(url: string | URL) {
        console.log('fromLegacy', url)
        if (typeof url === 'string') {
            if (url.slice(0, 3) !== 'http') {
                url = `${location.origin}/${url}`
            }
            url = new URL(url)
        }
        const pathParams = {
            module: url.searchParams.get('module'),
            action: url.searchParams.get('action'),
            record: url.searchParams.get('record'),
        }
        url.searchParams.delete('module')
        url.searchParams.delete('action')
        url.searchParams.delete('record')
        let path = '/'
        if (pathParams.module) {
            path += pathParams.module
            if (pathParams.action) {
                path += `/${pathParams.action}`
                if (pathParams.record) {
                    path += `/${pathParams.record}`
                }
            }
        }
        return path + url.search
    }

    return {
        module,
        action,
        record,
        toLegacyUrl,
        fromLegacyUrl,
    }
})
