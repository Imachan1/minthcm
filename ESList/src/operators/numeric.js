export default {
    equal: {
        label: 'LBL_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    not_equal: {
        label: 'LBL_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    gte: {
        label: 'LBL_GREATER_OR_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}' }}
        ]
    },
    lte: {
        label: 'LBL_LESS_OR_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'range', value: { lte: '{0}' }}
        ]
    },
    gt: {
        label: 'LBL_GREATER_THAN',
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'range', value: { gt: '{0}' }}
        ]
    },
    lt: {
        label: 'LBL_LESS_THAN',
        inputs: [
            { type: 'text', label: 'LBL_VALUE' }
        ],
        filters: [
            { op: 'range', value: { lt: '{0}' }}
        ]
    },
    between: {
        label: 'LBL_BETWEEN',
        inputs: [
            { type: 'text', label: 'LBL_VALUE_FROM' },
            { type: 'text', label: 'LBL_VALUE_TO' },
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{1}' }}
        ]
    },
}
