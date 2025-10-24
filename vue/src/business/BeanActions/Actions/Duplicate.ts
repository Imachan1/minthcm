import { BeanAction } from '../BeanAction'
import router from '@/router'

export class Duplicate extends BeanAction {
    public static readonly TITLE = 'LBL_DUPLICATE_BUTTON'
    public static readonly ICON = 'mdi-content-duplicate'
    public static readonly ACL = ['edit']

    public async execute() {
        router.push({
            path: `/modules/${this.bean.module}/EditView`,
            query: {
                copy_id: this.bean.id,
            },
        })
        return true
    }
}
