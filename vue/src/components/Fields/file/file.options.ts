import { usePreferencesStore } from '@/store/preferences'
import { FieldOptions } from '../Field.model'
import { useLanguagesStore } from '@/store/languages'

const options: FieldOptions = {
    validator: (value: any) => {
        if (!value || !(value instanceof File)) {
            return
        }
        const maxSize = usePreferencesStore().global?.upload_maxsize
        if (maxSize && value && value.size > maxSize) {
            const mb = Math.round(maxSize / 1000 / 1000)
            return useLanguagesStore().label('LBL_UPLOAD_MAXSIZE_EXCEEDED', null, { size_mb: mb })
        }
    },
}

export default options
