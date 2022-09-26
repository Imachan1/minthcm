export default {
    equals: {
        label: 'LBL_EQUALS',
        inputs: [
            { type: 'select', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    not_equals: {
        label: 'LBL_NOT_EQUALS',
        not: true,
        inputs: [
            { type: 'select', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    }
}