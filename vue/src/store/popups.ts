import { ref, markRaw, Component } from 'vue'
import { defineStore } from 'pinia'

export interface Popup {
    title: string
    component: Component
    icon?: string
    data?: object
}

export const usePopupsStore = defineStore('popups', () => {
    const popups = ref<Popup[]>([])

    function showPopup(popup: Popup) {
        popups.value.push({
            ...popup,
            component: markRaw(popup.component),
        })
    }

    function closePopup(popup: Popup) {
        popups.value = popups.value.filter((p) => p !== popup)
    }

    function closeAll() {
        popups.value = []
    }

    return {
        popups,
        showPopup,
        closePopup,
        closeAll,
    }
})
