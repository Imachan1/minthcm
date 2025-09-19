import { MintApi } from './api'

class UsersApi extends MintApi {
    public async isLoginUnique(username: string) {
        return await this.instance.post('Users/unique', {
            username: username,
        })
    }
}

export const usersApi = new UsersApi()
