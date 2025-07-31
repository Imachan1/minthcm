import { usePopupsStore } from '@/store/popups'
import { MassAction } from '../MassAction'
import axios from 'axios'
import MintPopupAlert from '@/components/MintPopups/MintPopupAlert.vue'
import { useLanguagesStore } from '@/store/languages'
export class MassConfirmation extends MassAction {
    public async execute() {
        const result = await axios.post(
            `legacy/index.php?&module=${this.module}&action=WSMassConfirmation&sugar_body_only=1&ids=${this.ids.join(
                ',',
            )}`,
        )

        const popupsStore = usePopupsStore()
        const languages = useLanguagesStore()
        if (result.status !== 200) {
            popupsStore.showPopup({
                component: MintPopupAlert,
                title: languages.label('LBL_ERROR'),
                icon: 'mdi-alert',
                data: {
                    text: languages.label('LBL_MASSCONFIRMATION_POPUP_ERROR', this.module),
                    onConfirm: () => {},
                },
            })
        } else {
            popupsStore.showPopup({
                component: MintPopupAlert,
                title: languages.label('LBL_ALT_INFO'),
                icon: 'mdi-information-outline',
                data: {
                    text: languages.label('LBL_MASSCONFIRMATION_POPUP_TEXT', this.module),
                    onConfirm: () => {},
                },
            })
        }

        return true
    }
}
