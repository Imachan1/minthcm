import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

export interface User {
    id: string
    is_admin: boolean
    first_name: string
    last_name: string
    full_name: string
}

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null)

    async function authenticate(username: string, password: string) {
        try {
            const response = await axios.post('/api/login', {
                username,
                password,
            })

        } catch {
            return false
        }
    }

    async function logout() {
        const response = await axios.post('/api/logout')
        location.href = '/minthcm/'
    }

    return {
        user,
        authenticate,
        logout,
    }
})
