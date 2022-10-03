export default {
    search: {
        label: 'LBL_SEARCH',
        inputs: [
            { type: 'text', label: 'LBL_SEARCH' }
        ],
        filters: [
            { op: 'wildcard', value: '{0}' }
        ]
    },
    search_not: {
        label: 'LBL_SEARCH_NOT',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_SEARCH' }
        ],
        filters: [
            { op: 'wildcard', value: '{0}' }
        ]
    }
}
