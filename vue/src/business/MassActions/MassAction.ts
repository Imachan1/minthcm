import { mintApi } from '@/api/api'
import { AxiosResponse } from 'axios'

export abstract class MassAction {
    protected module = ''
    protected ids: string[] = []

    public constructor(module: string, ids: string[]) {
        this.module = module
        this.ids = ids
    }

    public abstract execute(): Promise<boolean>

    protected async sendRequest(): Promise<AxiosResponse> {
        const className = this.constructor.name
        return await mintApi.post(`api/${this.module}/MassActions/${className}`, {
            ids: this.ids,
        })
    }
}
