import { MintApi } from './api'

class FavoritesApi extends MintApi {
    public async add(module: string, id: string) {
        return await this.instance.post('/favorites/add', {
            module: module,
            id: id,
        })
    }

    public async remove(module: string, id: string) {
        return await this.instance.post('/favorites/remove', {
            module: module,
            id: id,
        })
    }

    public async getList() {
        return await this.instance.get('/Favorites')
    }
}

export const favoritesApi = new FavoritesApi()
