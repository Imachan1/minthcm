import { ref } from 'vue'
import { defineStore } from 'pinia'

interface Favorite {
    id: string
    title: string
    module: string
    record: string
}

export const useFavoritesStore = defineStore('favorites', () => {
    const favorites = ref<Favorite[]>([])

    function fetch() {
        console.log('favorites fetch')
    }

    function removeFromFavorites(id: string) {
        console.log('removeFromFavorites', id)
    }

    function addToFavorites(module: string, record: string) {
        console.log('addToFavorites', module, record)
    }

    return {
        favorites,
        fetch,
        removeFromFavorites,
        addToFavorites,
    }
})
