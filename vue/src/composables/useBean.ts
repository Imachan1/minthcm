import axios from 'axios'
import { computed, ref, watch } from 'vue'
import { useLogic } from './useLogic'
import { useDebounceFn, useThrottleFn } from '@vueuse/core'
import { useRouter } from 'vue-router'

export const useBean = (module: string, id: string) => {
    const router = useRouter()

    const attributes = ref<{ [key: string]: any }>({})
    const syncAttributes = ref<{ [key: string]: any }>({})
    const aclAccess = ref<{ [key: string]: boolean }>({})
    const dirtyFields = ref(new Set<string>())

    const logic = useLogic(module)

    const attributesToSave = computed(() => {
        const attributesToSave = {} as { [key: string]: any }
        Object.keys(attributes.value).forEach((fieldName) => {
            if (logic.hiddenFields.value.includes(fieldName)) {
                return
            }
            attributesToSave[fieldName] = logic.readonlyFields.value.includes(fieldName)
                ? syncAttributes.value[fieldName]
                : attributes.value[fieldName]
        })
        return attributesToSave
    })

    const isRetrieving = ref(false)
    const isSaving = ref(false)
    const isDirty = ref(false)

    const validationError = ref('')
    const isValid = computed(() => {
        for (const fieldName of logic.requiredFields.value) {
            if ((isDirty.value || dirtyFields.value.has(fieldName)) && !attributes.value[fieldName]) {
                return false
            }
        }
        if (Object.keys(logic.errorMessages.value).length > 0) {
            return false
        }
        return true
    })

    const name = computed(() => {
        if (syncAttributes.value.name) {
            return syncAttributes.value.name
        }
        if (syncAttributes.value.first_name || syncAttributes.value.last_name) {
            return `${syncAttributes.value.first_name} ${syncAttributes.value.last_name}`
        }
        return id
    })

    const isNew = computed(() => {
        return !id || attributes.value.new_with_id
    })

    const isChanged = computed(() => {
        return Array.from(dirtyFields.value).some((f) => attributes.value[f] !== syncAttributes.value[f])
    })

    function restore() {
        attributes.value = { ...syncAttributes.value }
        dirtyFields.value.clear()
        isDirty.value = false
        validationError.value = ''
    }

    async function init() {
        await retrieve()
    }

    function updateFields(fields: { [fieldName: string]: any }) {
        Object.entries(fields || {}).forEach(([key, value]) => {
            attributes.value = {
                ...attributes.value,
                [key]: value,
            }
            dirtyFields.value.add(key)
        })
    }

    async function retrieve() {
        isRetrieving.value = true
        const response = await axios.get(`api/${module}/Get${id ? `/${id}` : ''}`)
        if (response.status === 200 && response.data) {
            aclAccess.value = response.data.acl_access
            attributes.value = response.data.attributes
            syncAttributes.value = structuredClone(response.data.attributes)
            logic.rules.value = response.data.logic?.rules ?? {}
            updateFields(logic.getUpdatedFields())
            dirtyFields.value = new Set()
        }
        isRetrieving.value = false
    }

    async function fetchLogic(triggerFields: string[] = []) {
        const response = await axios.post(`api/${module}/Logic${id ? `/${id}` : ''}`, {
            attributes: attributesToSave.value,
            triggerFields,
        })
        if (response.data.rules?.length) {
            response.data.rules.forEach((r: any) => {
                const rule = logic.rules.value.find((rule: any) => rule.key === r.key)
                if (rule) {
                    rule.trigger = r.trigger
                    rule.logic = r.logic
                }
            })
            updateFields(logic.getUpdatedFields(response.data.rules))
        }
    }

    async function save() {
        isDirty.value = true
        if (!isValid.value) {
            console.log('Invalid data')
            return false
        }
        isSaving.value = true
        try {
            const response = await axios.patch(`api/${module}/Update${id ? `/${id}` : ''}`, {
                record_data: attributesToSave.value,
            })
            if (!id && response.data.id) {
                router.push({
                    name: 'record',
                    params: {
                        module,
                        id: response.data.id,
                    },
                })
            } else if ([200, 201].includes(response.status)) {
                await retrieve()
            }
            return response
        } catch (error) {
            if (error?.response?.data?.isValid === false && error.response.data.error) {
                validationError.value = error.response.data.error
            }
            return false
        } finally {
            isSaving.value = false
        }
    }

    async function markDeleted() {
        return await axios.delete(`api/${module}/${id}`)
    }

    const prevAttributes = ref<{ [key: string]: any }>({})
    const compareChangesThrottled = useThrottleFn(
        () => {
            const newAttributes = JSON.parse(JSON.stringify(attributes.value))
            const updatedFields = {} as { [key: string]: any }
            Object.entries(prevAttributes.value).forEach(([key, value]) => {
                if (JSON.stringify(value) !== JSON.stringify(newAttributes[key])) {
                    dirtyFields.value.add(key)
                    updatedFields[key] = value
                }
            })
            const triggerFields = logic.triggerFields.value.filter((f) => Object.hasOwn(updatedFields, f))
            if (triggerFields.length > 0) {
                fetchLogic(triggerFields)
            }
            prevAttributes.value = newAttributes
        },
        1000,
        true,
    )
    const compareChangesDebounced = useDebounceFn(
        () => {
            compareChangesThrottled()
        },
        200,
        { maxWait: 2000 },
    )

    watch(
        attributes,
        () => {
            compareChangesDebounced()
        },
        { deep: true },
    )

    return {
        id,
        module,
        name,
        isNew,
        attributes,
        syncAttributes,
        aclAccess,
        dirtyFields,
        logic,
        isDirty,
        validationError,
        isValid,
        isRetrieving,
        isSaving,
        isChanged,
        init,
        updateFields,
        restore,
        retrieve,
        save,
        markDeleted,
    }
}
