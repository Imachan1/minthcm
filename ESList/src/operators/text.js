export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    }
}
