export default {
    search: {
        label: 'LBL_SEARCH',
        inputs: [
            { type: 'text', label: 'LBL_TEXT' }
        ],
        filters: [
            { op: 'wildcard', value: '{0}' }
        ]
    }
}