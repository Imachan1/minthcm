import { computed, ref, watch } from 'vue'
import { useLogic } from './useLogic'
import { useDebounceFn, useThrottleFn } from '@vueuse/core'
import { useRouter } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import { useField } from '@/components/Fields/useField'
import { useLink } from './useLink'
import { mintApi } from '@/api/api'

export const useBean = (module: string, id: string) => {
    const retrieveTimeoutTimeMs = 30000
    const router = useRouter()
    const modulesStore = useModulesStore()

    const attributes = ref<{ [key: string]: any }>({})
    const syncAttributes = ref<{ [key: string]: any }>({})
    const aclAccess = ref<{ [key: string]: boolean }>({})
    const dirtyFields = ref(new Set<string>())
    const links = ref<Map<string, ReturnType<typeof useLink>>>(new Map())

    const logic = useLogic(module)

    const duplicateSkipFields = [
        'id',
        'date_entered',
        'date_modified',
        'modified_user_id',
        'modified_by_name',
        'created_by',
        'created_by_name',
        'date_indexed',
    ]

    const filesToSave = ref<{ [key: string]: File }>({})
    const attributesToSave = computed(() => {
        const attributesToSave = {} as { [key: string]: any }
        Object.keys(attributes.value).forEach((fieldName) => {
            if (logic.hiddenFields.value.includes(fieldName)) {
                return
            }
            // attributesToSave[fieldName] = logic.readonlyFields.value.includes(fieldName)
            //     ? syncAttributes.value[fieldName]
            //     : attributes.value[fieldName]
            attributesToSave[fieldName] = attributes.value[fieldName]
        })
        return attributesToSave
    })

    const isRetrieving = ref(false)
    const isSaving = ref(false)
    const isDirty = ref(false)
    const loadingError = ref(false)

    const validationError = ref('')
    const isValid = computed(() => {
        if (logic.requiredFields.value) {
            for (const fieldName of logic.requiredFields.value) {
                if ((isDirty.value || dirtyFields.value.has(fieldName)) && !attributes.value[fieldName]) {
                    return false
                }
            }
        }
        if (Object.keys(errorMessages.value).length > 0) {
            return false
        }
        return true
    })

    const errorMessages = computed(() => {
        const formPanel = Object.values(modulesStore.modules[module]?.metadata.RecordView?.panels ?? {}).find( // FIXME: refactor - podobny kod w useLogic
            (panel) => panel.component === 'MintPanelRecordDetails',
        )
        
        const errors: { [key: string]: string } = {}
        if (!formPanel) {
            return errors
        }

        Object.values(formPanel?.data?.sections).forEach((section) => {
            const formFields = section?.fields?.flat() ?? []
            formFields.forEach((field) => {
                if (logic.hiddenFields.value.includes(field.name) || logic.readonlyFields.value.includes(field.name)) {
                    return
                }
                const value = filesToSave.value[field.name] ?? attributes.value[field.name]
                const fieldValidationResult = useField(field, value).validate()
                if (typeof fieldValidationResult === 'string') {
                    errors[field.name] = fieldValidationResult
                } else if (logic.errorMessages.value[field.name]) {
                    errors[field.name] = logic.errorMessages.value[field.name]
                }
            })
        })
        return errors
    })

    const fieldDefs = computed<{ [key: string]: any }>(() => {
        return modulesStore.modules[module]?.vardefs ?? {}
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
        filesToSave.value = {}
        dirtyFields.value.clear()
        isDirty.value = false
        validationError.value = ''
    }

    async function init() {
        return retrieve()
    }

    function updateFields(fields: { [fieldName: string]: any }) {
        Object.entries(fields || {}).forEach(([key, value]) => {
            if (value instanceof File) {
                filesToSave.value[key] = value
                value = value?.name ?? ''
            }
            attributes.value = {
                ...attributes.value,
                [key]: value,
            }
            dirtyFields.value.add(key)
        })
    }

    function setAttributesFromQuery(query: { [key: string]: string | (string | null)[] | null | undefined }) {
        const fieldsToUpdate: { [fieldName: string]: any } = {}
        Object.entries(query)
            .filter(([key]) => fieldDefs.value[key])
            .map(([key, value]) => {
                fieldsToUpdate[key] = value
            })
        if (query?.return_relationship && query?.return_id) {
            const link = loadRelationship(query.return_relationship as string)
            if (link && !link.relateFieldName) link.add(query.return_id as string)
        }
        updateFields(fieldsToUpdate)
        const triggerFields = logic.triggerFields.value.filter((f) => Object.hasOwn(fieldsToUpdate, f))
        if (triggerFields.length > 0) {
            fetchLogic(triggerFields)
        }
    }

    async function setAttributesFromBeanId(copy_id: string) {
        const fieldsToUpdate: { [fieldName: string]: any } = {}
        const copyBean = await useBean(module, copy_id).init()
        Object.entries(copyBean.data.attributes || {}).forEach(([fieldName, fieldDef]) => {
            if (
                duplicateSkipFields.includes(fieldName) 
                || ['file', 'image'].includes(fieldDefs.value[fieldName].type)
            ) return
            if (copyBean.data.attributes[fieldName] !== undefined 
                && copyBean.data.attributes[fieldName] !== null
                && copyBean.data.attributes[fieldName] !== ''
            ) {
                fieldsToUpdate[fieldName] = copyBean.data.attributes[fieldName]
            }
        })
        updateFields(fieldsToUpdate)
    }

    async function retrieve() {
        isRetrieving.value = true
        loadingError.value = false
        const timeout = new Promise((_, reject) =>
            setTimeout(() => reject(new Error('timeout')), retrieveTimeoutTimeMs),
        )

        const apiCall = mintApi
            .get(`${module}/Get${id ? `/${id}` : ''}`, { rawError: true })
            .then((response) => {
                if (response.status === 200 && response.data) {
                    setData(response.data)
                }
                return response
            })
            .finally(() => {
                isRetrieving.value = false
            })

        try {
            return await Promise.race([apiCall, timeout])
        } catch (error) {
            if (error?.message === 'timeout') {
                loadingError.value = true
                throw { response: { status: 408, data: { error: 'Request timed out' } } }
            }
            throw error
        } finally {
            isRetrieving.value = false
        }
    }

    function setData(data: { [key: string]: any }) {
        aclAccess.value = data.acl_access
        attributes.value = data.attributes
        syncAttributes.value = structuredClone(data.attributes)
        logic.rules.value = data.logic?.rules ?? {}
        updateFields(logic.getUpdatedFields())
        dirtyFields.value = new Set()
    }

    async function fetchLogic(triggerFields: string[] = []) {
        const response = await mintApi.post(`${module}/Logic${id ? `/${id}` : ''}`, {
            attributes: attributes.value,
            triggerFields
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

    function loadRelationship(name: string): ReturnType<typeof useLink> | null {
        if (!name) return null
        if (!Object.keys(fieldDefs.value?.[name] || {}).length) {
            const relatedLinkField = Object.keys(fieldDefs.value)
                .find(fieldName => fieldDefs.value?.[fieldName]?.type === 'link'
                    && fieldDefs.value?.[fieldName]?.relationship === name)
            if (!relatedLinkField) return null
            name = relatedLinkField
        } else if (fieldDefs.value[name]?.type !== 'link' || !fieldDefs.value[name]?.relationship) return null
        if (!links.value.has(name)) links.value.set(name, useLink(name, fieldDefs.value[name].relationship, { module, id, fieldDefs }))
        return links.value.get(name)
    }

    async function save() {
        isDirty.value = true
        if (!isValid.value) {
            return {
                status: false,
                error: 'validation_failed',
                errorMessages: errorMessages.value,
                validationError: validationError.value,
            }
        }
        isSaving.value = true
        try {
            const files = {}
            for (const fileField in filesToSave.value) {
                files[fileField] = await new Promise((resolve, reject) => {
                    if (filesToSave.value[fileField]?.size === 0) {
                        resolve(null)
                        return
                    }
                    const reader = new FileReader()
                    reader.onload = () => resolve(reader.result)
                    reader.onerror = reject
                    reader.readAsDataURL(filesToSave.value[fileField])
                })
            }
            const response = await mintApi.patch(`${module}/Update${id ? `/${id}` : ''}`, {
                record_data: attributesToSave.value,
                files,
                links: Object.fromEntries([...links.value].map(([name, link]) => [name, link.getChanges()]))
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
            return {
                status: false,
                error: 'save_request_failed',
                errorMessages: errorMessages.value,
                validationError: validationError.value,
            }
        } finally {
            isSaving.value = false
        }
    }

    async function markDeleted() {
        return await mintApi.delete(`${module}/${id}`)
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
        errorMessages,
        init,
        updateFields,
        restore,
        retrieve,
        setData,
        save,
        markDeleted,
        fieldDefs,
        setAttributesFromQuery,
        loadRelationship,
        setAttributesFromBeanId,
    }
}
