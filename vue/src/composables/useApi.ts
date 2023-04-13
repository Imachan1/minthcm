import axios from 'axios'

axios.defaults.baseURL = import.meta.env.BASE_URL

export function useApi() {
    async function get(url: string) {
        const result = await axios.get(url)
        return result
    }

    return {
        get,
    }
}
