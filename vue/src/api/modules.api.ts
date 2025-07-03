import { MintApi } from './api'

class ModulesApi extends MintApi {
    public async getListInit(module_name: string) {
        return await this.instance.get(module_name)
    }

    public async getListData(module_name: string, options: any, myObjects: any, searchPhrase: any, filters: any, defs: any, activeFilter: any, pageOffsetMap: any) {
        return await this.instance.post(module_name, {
            page: options.value.page,
            items: options.value.itemsPerPage === -1 ? 100 : options.value.itemsPerPage,
            myObjects: myObjects.value,
            searchPhrase: searchPhrase.value,
            filters: filters.value,
            sortBy: defs.value?.columns[options.value.sortBy[0]?.key]?.key,
            sortOrder: options.value.sortBy[0]?.order ?? 'asc',
            activeFilter: activeFilter.value,
        })
    }

    public async forgetPassword(username: string, email: string) {
        return await this.instance.post('api/forget_password', {
            data: {
                username,
                email,
            },
        })
    }

    public async saveListPreferences(module_name: string, preferences: any) {
        return await this.instance.post(module_name + '/list/preferences', {
            preferences: preferences,
        })
    }
}

export const modulesApi = new ModulesApi()
