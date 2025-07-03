import { MintApi } from './api'

interface GlobalSearchParams {
    query: string
    itemsPerPage?: string
    page?: number
}

class UnifiedSearchApi extends MintApi {
    public async globalSearch(params: GlobalSearchParams) {
        return await this.instance.get('/global_search', { params })
    }
}

export const unifiedSearchApi = new UnifiedSearchApi()
