export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: '{0}' }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: '{0}' }
        ]
    }
}
