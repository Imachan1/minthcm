import { useModulesStore } from '@/store/modules'
import { computed, ref } from 'vue'

interface Logic {
    errors: { [fieldName: string]: string }
    update: { [fieldName: string]: any }
    readonly: { [fieldName: string]: boolean }
    required: { [fieldName: string]: boolean }
    visible: { [fieldName: string]: boolean }
    options: { [fieldName: string]: any }
}

interface Rule {
    key: string
    triggerFields: string[]
    hooks: string[]
    trigger: boolean
    logic: Logic
}

export const useLogic = (module: string) => {
    const modulesStore = useModulesStore()

    const rules = ref<Rule[]>([])

    const formFields = computed(() => { // FIXME: refactor - moim zdaniem to nie jest miejsce na ta funkcje raczej ModuleStore
        const formPanel = Object.values(modulesStore.modules[module]?.metadata.RecordView?.panels ?? {}).find(
            (panel) => panel.component === 'MintPanelRecordDetails',
        )
        return (formPanel?.data?.fields?.flat() ?? []).map((field) => field.name)
    })

    const activeRules = computed(() => rules.value.filter((rule) => rule.trigger))

    const rulesTriggeredOnChange = computed(() =>
        rules.value.filter((rule) => rule.hooks.some((hook) => ['all', 'change'].includes(hook))),
    )

    const triggerFields = computed(() => {
        const triggerFields = [] as string[]
        rulesTriggeredOnChange.value.forEach((s) => {
            if (s.triggerFields) {
                s.triggerFields.forEach((fieldName) => {
                    if (!triggerFields.includes(fieldName)) {
                        triggerFields.push(fieldName)
                    }
                })
            }
        })
        return triggerFields
    })

    const readonlyFields = computed(() => {
        const readonlyFields = [] as string[]
        activeRules.value.forEach((s) => {
            Object.entries(s.logic.readonly).forEach(([fieldName, value]) => {
                if (value) {
                    readonlyFields.push(fieldName)
                } else if (readonlyFields.includes(fieldName)) {
                    readonlyFields.splice(readonlyFields.indexOf(fieldName), 1)
                }
            })
        })
        return readonlyFields
    })

    const requiredFields = computed(() => {
        const requiredFields = [] as string[]
        activeRules.value.forEach((s) => {
            Object.entries(s.logic.required).forEach(([fieldName, value]) => {
                if (
                    value &&
                    formFields.value.includes(fieldName) &&
                    !hiddenFields.value.includes(fieldName) &&
                    !readonlyFields.value.includes(fieldName)
                ) {
                    requiredFields.push(fieldName)
                } else if (requiredFields.includes(fieldName)) {
                    requiredFields.splice(requiredFields.indexOf(fieldName), 1)
                }
            })
        })
        return requiredFields
    })

    const hiddenFields = computed(() => {
        const hiddenFields = [] as string[]
        activeRules.value.forEach((s) => {
            Object.entries(s.logic.visible).forEach(([fieldName, value]) => {
                if (!formFields.value.includes(fieldName) || !value) {
                    hiddenFields.push(fieldName)
                } else if (hiddenFields.includes(fieldName)) {
                    hiddenFields.splice(hiddenFields.indexOf(fieldName), 1)
                }
            })
        })
        return hiddenFields
    })

    const errorMessages = computed(() => {
        const errorMessages = {} as { [fieldName: string]: string }
        activeRules.value.forEach((s) => {
            Object.entries(s.logic.errors).forEach(([fieldName, value]) => {
                if (value) {
                    errorMessages[fieldName] = value
                }
            })
        })
        return errorMessages
    })

    const fieldsOptions = computed(() => {
        const options: { [fieldName: string]: any } = {}
        activeRules.value.forEach((s) => {
            Object.entries(s.logic.options ?? {}).forEach(([fieldName, value]) => {
                if (value && formFields.value.includes(fieldName)) {
                    options[fieldName] = value
                }
            })
        })
        return options
    })

    function getUpdatedFields(rules: Rule[] | null = null) {
        rules = rules || activeRules.value
        const updatedFields = {} as { [fieldName: string]: any }
        rules.forEach((rule) => {
            Object.entries(rule.logic.update ?? {}).forEach(([fieldName, value]) => {
                updatedFields[fieldName] = value
            })
        })
        return updatedFields
    }

    return {
        rules,
        rulesTriggeredOnChange,
        triggerFields,
        readonlyFields,
        requiredFields,
        hiddenFields,
        errorMessages,
        fieldsOptions,
        getUpdatedFields,
    }
}
