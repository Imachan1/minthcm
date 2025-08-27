import { SubpanelAction } from '../SubpanelAction'
import router from '@/router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'

export class Create extends SubpanelAction {
    public static readonly TITLE = 'LBL_CREATE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-plus'
    public static readonly ACL = ['edit']

    public async execute() {
        const store = useRecordViewStore()
        router.push({
            name: 'module-view',
            params: { module: this.subpanel.module, action: 'EditView' },
            query: {
                return_action: 'DetailView',
                parent_id: store.bean.id,
                return_id: store.bean.id,
                return_module: store.bean.module_name,
                parent_type: store.bean.module_name,
                parent_name: store.bean.attributes.name,
                candidate_id: store.bean.module_name === 'Candidates' ? store.bean.id : null,
                candidate_name: store.bean.module_name === 'Candidates' ? store.bean.attributes.name : null,
                employee_id: store.bean.module_name === 'Employees' ? store.bean.id : null,
                employee_name: store.bean.module_name === 'Employees' ? store.bean.attributes.name : null,
                employees_name: store.bean.module_name === 'Employees' ? store.bean.attributes.name : null,
            },
        })
        return true
    }
}
