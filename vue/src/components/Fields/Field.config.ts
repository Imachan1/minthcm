const listFields = import.meta.glob('@/components/Fields/*/*.list.vue', { eager: true })
const editFields = import.meta.glob('@/components/Fields/*/*.edit.vue', { eager: true })
const detailFields = import.meta.glob('@/components/Fields/*/*.detail.vue', { eager: true })
const fieldsOptions = import.meta.glob('@/components/Fields/*/*options.ts', { eager: true })

const getFieldName = (path: string) => path.split('/').slice(-2, -1)[0]

export const fieldConfig = {
    allowedTypes: {
        list: Object.keys(listFields).map(getFieldName),
        edit: Object.keys(editFields).map(getFieldName),
        detail: Object.keys(detailFields).map(getFieldName),
    },

    options: Object.fromEntries(
        Object.entries(fieldsOptions).map(([path, module]) => {
            const name = getFieldName(path)
            const m = module as { default: any }
            return [name, m.default ?? m]
        }),
    ),

    defaultType: 'varchar',

    typeMap: {
        char: 'varchar',
        datetimecombo: 'datetime',
        ColoredActivityStatus: 'enum',
        ColoredEnum: 'enum',
        image: 'file',
    } as { [key: string]: string },
}
