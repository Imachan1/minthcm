import { RouteRecordRaw } from 'vue-router'
import masqueradeRoutes from './masquerade.routes'

const customRoutes: Array<RouteRecordRaw> = [
    ...masqueradeRoutes
]


export default customRoutes
