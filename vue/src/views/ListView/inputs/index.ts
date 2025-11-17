export { default as date } from './date'
export { default as text } from './text'
export { default as numeric } from './numeric'
export { default as select } from './select'
export { default as multiselect } from './multiselect'
export { default as relate } from './relate'
export { default as bool } from './bool'

export const defaultInput = 'text'

export const typeMap = {
    datetime: 'date',
    datetimecombo: 'date',
    boolean: 'bool',
    int: 'numeric',
    float: 'numeric',
    decimal: 'numeric',
    currency: 'numeric',
    ColoredActivityStatus: 'select',
    ColoredEnum: 'select',
    name: 'text',
    multienum: 'multiselect',
    enum: 'select',
}