import { FieldVardef } from '@/store/modules'
import { fieldConfig } from './Field.config'
import { FieldOptions, FieldValidator } from './Field.model'

export const useField = (defs: FieldVardef, value: any) => {
    const resolvedFieldType = fieldConfig.typeMap[defs.type] || defs.type

    const fieldOptions: FieldOptions = fieldConfig.options[resolvedFieldType] || {}

    function validate(): ReturnType<FieldValidator> {
        if (typeof fieldOptions.validator === 'function') {
            return fieldOptions.validator(value)
        }
    }

    return {
        validate,
    }
}
