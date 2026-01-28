import { computed, ComputedRef, ref } from 'vue'
import { modulesApi } from '@/api/modules.api'
import { mintApi } from '@/api/api'

interface RelationshipRecord {
    id: string
    additionalValues?: { [key: string]: string }
}

interface BeanData {
    id: string
    module: string
    fieldDefs: ComputedRef
}

export const useLink = (link: string, relationshipName: string, beanData: BeanData) => {
    const beans = ref<Map<string, { [key: string]: any }>>(new Map())
    const beansToAdd = ref<Map<string, RelationshipRecord>>(new Map())
    const beansToRemove = ref<Set<string>>(new Set())

    const relateFieldName = computed<string | null>(() => {
        const relateField = Object.keys(beanData.fieldDefs.value).find(
            (fieldName) =>
                beanData.fieldDefs.value?.[fieldName]?.type === 'relate' &&
                (beanData.fieldDefs.value?.[fieldName]?.link === link ||
                    beanData.fieldDefs.value?.[fieldName]?.relationship === relationshipName),
        )
        return relateField || null
    })

    const idFieldName = computed<string | null>(() => {
        const idField = Object.keys(beanData.fieldDefs.value).find(
            (fieldName) =>
                beanData.fieldDefs.value?.[fieldName]?.type === 'id' &&
                (beanData.fieldDefs.value?.[fieldName]?.link === relationshipName ||
                    beanData.fieldDefs.value?.[fieldName]?.relationship === relationshipName),
        )
        return idField || null
    })

    function add(id: string, additionalValues?: { [key: string]: string }) {
        if (!id || beansToAdd.value.has(id)) return
        beansToAdd.value.set(id, { id, additionalValues })
        beansToRemove.value.delete(id)
    }

    function remove(id: string) {
        if (!id || beansToRemove.value.has(id)) return
        beansToRemove.value.add(id)
        beansToAdd.value.delete(id)
    }

    async function unlink(parentBean: any, link_name: string)
    {
        await mintApi.post(`/${parentBean.module}/Unlink/${parentBean.id}`, {
                ids: [...beansToRemove.value],
                link_name: link_name,
        })
    }

    // TODO: to trzeba zmienić, żeby po pobraniu rekordów przerabiało je na useBean
    // TODO: Użyć tego do pobierania danych do subpaneli i dołączyć stronnicowanie
    async function fetchRelatedRecords() {
        const result = await modulesApi.fetchRelatedRecords(
            beanData.module,
            link,
            beanData.id,
        )
        if (result.data) {
            beans.value = new Map(Object.entries(result.data))
        }
    }

    function getChanges() {
        return {
            beansToAdd: Object.fromEntries(beansToAdd.value),
            beansToRemove: Array.from(beansToRemove.value),
        }
    }

    return {
        link,
        relationshipName,
        beans,
        relateFieldName,
        idFieldName,
        add,
        remove,
        fetchRelatedRecords,
        getChanges,
        unlink,
    }
}
