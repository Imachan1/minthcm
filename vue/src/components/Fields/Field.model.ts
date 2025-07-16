import { useBean } from '@/composables/useBean'
import { FieldVardef } from '@/store/modules'

export interface FieldProps {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: {
        bean: ReturnType<typeof useBean>
    }
    options?: any
    state?: FieldState
    required?: boolean
    error?: boolean
}

export type FieldState = 'normal' | 'error' | 'required'

export type FieldValidator = (value: any) => string | void

export interface FieldOptions {
    validator?: FieldValidator
}
