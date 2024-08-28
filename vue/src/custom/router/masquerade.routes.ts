import { RouteRecordRaw } from 'vue-router'
import axios from 'axios'
import LegacyView from '@/views/LegacyView/LegacyView.vue'

const masqueradeRoutes: Array<RouteRecordRaw> = [
    {
        path: '/modules/Users/Masquerade/:record',
        name: 'masquerade',
        component: LegacyView,
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=Users&action=Masquerade&record',
            auth: true,
        },
        beforeEnter: async (to) => {
            await axios.post("legacy/index.php?module=Users&action=Masquerade&record=" + to.params?.record)
            document.location.href = document.location.pathname
        },
    },
    {
        path: '/modules/Users/Unmasquerade',
        name: 'unmasquerade',
        component: LegacyView,
        meta: {
            isLegacy: true,
            legacyUrl: 'legacy/index.php?module=Users&action=Unmasquerade',
            auth: true,
        },
        beforeEnter: async () => {
            await axios.post("legacy/index.php?module=Users&action=Unmasquerade")
            document.location.href = document.location.pathname
        },
    },
]


export default masqueradeRoutes