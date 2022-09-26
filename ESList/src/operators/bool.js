export default {
    equals: {
        label: 'LBL_EQUALS',
        inputs: [
            { type: 'enum', options: 'yes_no_list', operator: '=' }
        ]
    },
    not_equals: {
        label: 'LBL_NOT_EQUALS',
        inputs: [
            { type: 'enum', options: 'yes_no_list', operator: '!=' }
        ]
    },
}
