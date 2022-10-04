export default {
    equal: {
        label: 'LBL_EQUAL',
        inputs: [
            { type: 'select', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    not_equal: {
        label: 'LBL_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'select', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    contain: {
        label: 'LBL_CONTAIN',
        inputs: [
            { type: 'multiselect', label: 'LBL_VALUES' }
        ],
        filters: [
            { op: 'terms', value: '{0}' }
        ]
    },
    not_contain: {
        label: 'LBL_NOT_CONTAIN',
        not: true,
        inputs: [
            { type: 'multiselect', label: 'LBL_VALUES' }
        ],
        filters: [
            { op: 'terms', value: '{0}' }
        ]
    },
}