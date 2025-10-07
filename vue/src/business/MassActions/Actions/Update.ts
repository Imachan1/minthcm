import { MassAction } from '../MassAction'
import { useListViewStore } from '@/views/ListView/ListViewStore'
import { storeToRefs } from 'pinia'


export class Update extends MassAction {
    public async execute() {
        useListViewStore().setMassUpdate(true)
        return false
    }

    public async executeMassUpdate() {
        const { massUpdateRows } = storeToRefs(useListViewStore())
        let data = { update_fields: {} as any }
        let result = {
            data: {
                success: false
            }
        }
        massUpdateRows.value.forEach(row => {
            if (row.inputs[0] && row.inputs[0].value !== undefined && !Number.isNaN(row.inputs[0].value)) {
                data.update_fields[row.field] = row.inputs[0].value
            }
        })
        
        if (Object.values(data.update_fields).length <= 0) {
            return result
        }
        
        result = await this.sendRequest(data)
        return result
    }
}
