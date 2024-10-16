import { useMintWallStore } from '@/components/MintWall/MintWallStore'
import MintWall from '@/components/MintWall/MintWall.vue'

export default {
    icon: 'mdi-newspaper-variant',
    component: MintWall,
    onScroll: (drawerElement: HTMLElement) => {
        if (!drawerElement) {
            return
        }
        const store = useMintWallStore()
        store.loadNews()
    },
}
