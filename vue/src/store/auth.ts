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
        const response = await axios.post('/api/login', {
            username,
            password,
        })
        console.log('auth', response)
        // console.log('authenticate', username, password)
    }

    async function logout() {
        user.value = null
        console.log('logout')
    }

    return {
        user,
        authenticate,
        logout,
    }
})
