export default {
    last_7_days: {
        label: 'LBL_LAST_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-7d', lte: 'now' }}
        ]
    },
    next_7_days: {
        label: 'LBL_NEXT_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now', lte: 'now+7d' }}
        ]
    },
    last_30_days: {
        label: 'LBL_LAST_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-30d', lte: 'now' }}
        ]
    },
    next_30_days: {
        label: 'LBL_NEXT_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now', lte: 'now+30d' }}
        ]
    },
    after: {
        label: 'LBL_AFTER',
        inputs: [
            { type: 'date',label: 'LBL_DATE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}' }}
        ]
    },
    between: {
        label: 'LBL_BETWEEN',
        inputs: [
            { type: 'date', label: 'LBL_FROM' },
            { type: 'date', label: 'LBL_TO' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{1}' }}
        ]
    },
}
