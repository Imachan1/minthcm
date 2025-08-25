const listFields = import.meta.glob('@/components/Fields/*/*.list.vue')
const editFields = import.meta.glob('@/components/Fields/*/*.edit.vue')
const detailFields = import.meta.glob('@/components/Fields/*/*.detail.vue')
const options = import.meta.glob('@/components/Fields/*/*options.ts')

export const fieldConfig = {
    allowedTypes: {
        list: Object.keys(listFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
        edit: Object.keys(editFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
        detail: Object.keys(detailFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
    },
    options: Object.fromEntries(
        await Promise.all(
            Object.entries(options).map(async ([path, module]) => {
                const name = path.match(/Fields\/(\w*)\/file\.options/)?.[1]
                const mod = (await module()) as { default: any }
                return [name, mod.default]
            }),
        ),
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
