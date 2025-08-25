export default {
    last_7_days: {
        label: 'LBL_ESLIST_LAST_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-7d/d', lte: 'now/d' }}
        ]
    },
    next_7_days: {
        label: 'LBL_ESLIST_NEXT_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now/d', lte: 'now+7d/d' }}
        ]
    },
    last_30_days: {
        label: 'LBL_ESLIST_LAST_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-30d/d', lte: 'now/d' }}
        ]
    },
    next_30_days: {
        label: 'LBL_ESLIST_NEXT_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now/d', lte: 'now+30d/d' }}
        ]
    },
    after: {
        label: 'LBL_ESLIST_AFTER',
        inputs: [
            { type: 'date',label: 'LBL_ESLIST_DATE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}' }}
        ]
    },
    before: {
        label: 'LBL_ESLIST_BEFORE',
        inputs: [
            { type: 'date',label: 'LBL_ESLIST_DATE' }
        ],
        filters: [
            { op: 'range', value: { lte: '{0}' }}
        ]
    },
    between: {
        label: 'LBL_ESLIST_BETWEEN',
        inputs: [
            { type: 'date', label: 'LBL_ESLIST_FROM' },
            { type: 'date', label: 'LBL_ESLIST_TO' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{1}' }}
        ]
    },
}
