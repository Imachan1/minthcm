<template>
    <div class="record-view">
        <div class="record-panels">
            <MintPanel v-for="panel in store.panels" :key="panel.key" :component="panel.component" :data="panel.data" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, watch, defineEmits } from 'vue' //FIXME CR - czy defineEmits jest potrzebne?
import MintPanel from '@/components/MintPanel/MintPanel.vue'
import { useRecordViewStore } from './RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import { useRoute } from 'vue-router'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { useRouter } from 'vue-router'
import { useACL } from '@/composables/useACL'

const store = useRecordViewStore()
const languages = useLanguagesStore()
const backend = useBackendStore()
const route = useRoute()
const router = useRouter()

store.resetBean()

onMounted(async () => {
    await store.bean.init().catch(recordAccessError)
    if (store.bean.isNew) {
        store.view = 'edit'
        if (Object.keys(route.query).length) {
            store.bean.setAttributesFromQuery(route.query)
        }

        if (Object.keys(route.query).includes('copy_id')) {
            await store.bean.setAttributesFromBeanId(route.query.copy_id as string)
        }
    }
})

function recordAccessError(error: any): Promise<any> {
    if ([403, 404].includes(error.response.status)) {
        useStatusBoxesStore().showStatus('record_access_error', {
            type: 'error',
            message: useLanguagesStore().label('ERROR_NO_RECORD'),
            autoClose: true,
        })
        useACL().hasAccess(store.bean.module, 'list', true, true) ?
            router.push({ name: 'list', params: { module: store.bean.module } }) :
            router.push({ name: 'dashboard' })
    }
    return Promise.reject(error)
}

watch(
    () => store.bean.syncAttributes,
    (newVal) => {
        if (!newVal.name || !newVal.module_name) return
        document.title = `${newVal.name} | ${languages.label('LBL_MODULE_NAME', newVal.module_name)} | ${backend.initData?.systemName}`
    },
)
</script>

<style scoped lang="scss">
.record-view {
    padding: 32px;
}

.record-panels {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
</style>
