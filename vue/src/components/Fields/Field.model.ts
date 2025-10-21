import { useBean } from '@/composables/useBean'
import { FieldVardef } from '@/store/modules'

export interface FieldProps {
    view: 'edit' | 'detail' | 'list'
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
    options?: any
    state?: FieldState
    required?: boolean
    error?: boolean
    errorMessage?: string
    disabled?: boolean
    hidePencil?: boolean
    isDirty?: boolean
}

export type FieldState = 'normal' | 'error' | 'required'

export type FieldValidator = (value: any) => string | void

export interface FieldOptions {
    validator?: FieldValidator
}
