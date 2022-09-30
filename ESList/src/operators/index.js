export {default as date} from './date'
export {default as bool} from './bool'
export {default as enum} from './enum'
export {default as text} from './text'
export {default as numeric} from './numeric'

export const defaultOperator = 'text'

export const typeMap = {
    datetime: 'date',
    datetimecombo: 'date',
    multienum: 'enum',
    boolean: 'bool',
    int: 'numeric',
    float: 'numeric',
    decimal: 'numeric'
}
