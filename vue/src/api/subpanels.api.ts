import { MintApi } from './api'

class SubpanelsApi extends MintApi {
    public async fetchSubpanelsData(module: string | string[], subpanelKey: string, recordId: string | string[], paginateBy: number = -1, page: number = 0) {
        return await this.instance.get(`${module}/subpanel/${subpanelKey}/${recordId}`, {
            validateStatus: () => true,
            params: {
                paginate_by: paginateBy,
                page: page || 0,
            },
        })
    }
}

export const subpanelsApi = new SubpanelsApi()
