import { useMintWallStore } from '@/components/MintWall/MintWallStore'
import MintWall from '@/components/MintWall/MintWall.vue'
import { useACL } from '@/composables/useACL'

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
    isAvaliable: () => {
        return useACL().hasAccess('News', 'list', true)
    },
}
