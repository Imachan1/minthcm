import { ref } from 'vue'
import { defineStore } from 'pinia'

interface GlobalPreferences {
    [key: string]: any
}

interface UserPreferences {
    [key: string]: any
}

export const usePreferencesStore = defineStore('preferences', () => {
    const global = ref<GlobalPreferences | null>(null)
    const user = ref<UserPreferences | null>(null)

    function getFirstNameFieldByPreference(): 'first_name' | 'last_name' {
        const first = user.value?.name_format.toLowerCase().split('').find(c => c === 'f' || c === 'l')
        if (first === 'f') return 'first_name'
        if (first === 'l') return 'last_name'
        return 'first_name'
    }

    return {
        global,
        user,
        getFirstNameFieldByPreference,
    }
})
