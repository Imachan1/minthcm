import relateInput from '../inputs/relate'

export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            relateInput,
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            relateInput,
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    }
}
