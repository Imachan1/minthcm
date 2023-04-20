import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

interface Alert {
    id: string
    title: string
    date: string
    is_read: boolean
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
        setTimeout(() => {
            alerts.value = [
                {
                    id: '1',
                    title: 'Your work schedule from 12.04 has not been confirmed. Bla bla bla bla bla.',
                    date: '2023-04-19 13:25:00',
                    is_read: false,
                },
                {
                    id: '2',
                    title: 'Your work schedule from 11.04 has not been confirmed. Bla bla bla bla bla.',
                    date: '2023-04-15 23:30:00',
                    is_read: true,
                },
                {
                    id: '3',
                    title: 'Your work schedule from 10.04 has not been confirmed. Bla bla bla bla bla. Your work schedule from 10.04 has not been confirmed. Bla bla bla bla bla.',
                    date: '2023-01-14 05:45:37',
                    is_read: false,
                },
            ]
            isFetching.value = false
        }, 3000)
    }

    const unreadAlertsCount = computed(() => {
        return alerts.value.filter((alert) => !alert.is_read).length
    })

    return {
        init,
        alerts,
        unreadAlertsCount,
    }
})
