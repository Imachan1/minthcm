import { mintApi } from './api'

class ModulesApi {
    public async getListInit(module_name: string) {
        return await mintApi.get(`${module_name}`)
    }

    public async getListData(
        module_name: string,
        searchPhrase = '',
        filters = {},
        page = 0,
        itemsPerPage = 100,
        myObjects = false,
        sortBy: string | null = null,
        sortOrder = 'asc',
        activeFilter = null,
    ) {
        return await mintApi.post(module_name, {
            page: page,
            items: itemsPerPage,
            myObjects: myObjects,
            searchPhrase: searchPhrase,
            filters: filters,
            sortBy: sortBy,
            sortOrder: sortOrder,
            activeFilter: activeFilter,
        })
    }

    public async forgetPassword(username: string, email: string) {
        return await mintApi.post('forget_password', {
            data: {
                username,
                email,
            },
        })
    }

    public async saveListPreferences(module_name: string, preferences: any) {
        return await mintApi.post(module_name + '/list/preferences', {
            preferences: preferences,
        })
    }

    public async fetchRelatedRecords(module: string, link: string, id: string) {
        return await mintApi.get(`${module}/subpanel/${link}/${id}`)
    }
}

export const modulesApi = new ModulesApi()
