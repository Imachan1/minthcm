import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

interface Alert {
    id: string
    name: string
    description: string
    date_entered: string
    is_read: boolean
    parent_id: string
    parent_type: string
}

export const useAlertsStore = defineStore('alerts', () => {
    const FETCH_INTERVAL_MS = 1000 * 60
    const alerts = ref<Alert[]>([])
    const isFetching = ref(false)

    function init() {
        fetchAlerts()
        setInterval(fetchAlerts, FETCH_INTERVAL_MS)
    }

    async function fetchAlerts() {
        if (isFetching.value) {
            return
        }
        isFetching.value = true
        const response = await axios.get('/api/Alerts')
        console.log('alerts', response.data)
        alerts.value = response.data
        isFetching.value = false
    }

    async function markRead(id: string) {
        const response = await axios.patch(`/api/Alerts/${id}`, {
            is_read: true,
        })
        fetchAlerts()
        console.log('markRead', response)
    }

    async function close(id: string) {
        const response = await axios.patch(`/api/Alerts/${id}`, {
            is_closed: true,
        })
        fetchAlerts()
        console.log('close', response)
    }

    const unreadAlertsCount = computed(() => {
        return alerts.value.filter((alert) => !alert.is_read).length
    })

    const sortedAlerts = computed(() => {
        return [...alerts.value].sort((a, b) => a.date_entered < b.date_entered ? 1 : -1)
    })

    return {
        init,
        markRead,
        close,
        alerts,
        unreadAlertsCount,
        sortedAlerts,
    }
})
