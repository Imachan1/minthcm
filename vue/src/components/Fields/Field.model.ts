import { FieldVardef } from '@/store/modules'

export interface FieldProps {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
    state?: FieldState
    required?: boolean
    error?: boolean
}

export type FieldState = 'normal' | 'error' | 'required'
