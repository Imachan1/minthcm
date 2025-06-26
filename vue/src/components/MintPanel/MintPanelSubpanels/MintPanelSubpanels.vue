<template>
    <div class="subpanels-panel">
        <h1>{{ languages.label('LBL_RELATED_RECORDS') }}</h1>
        <v-expansion-panels v-model="expandedSubpanels" class="subpanels-accordion" multiple variant="accordion">
            <v-expansion-panel
                v-for="subpanel in store.subpanels"
                :key="subpanel.key"
                bg-color="transparent"
                :value="subpanel.key"
                :class="['mint-subpanel', !subpanel.records?.length && 'mint-subpanel-disabled']"
            >
                <v-expansion-panel-title class="mint-subpanel-title" hide-actions>
                    <div>
                        <v-icon
                            :icon="expandedSubpanels.includes(subpanel.key) ? 'mdi-chevron-down' : 'mdi-chevron-right'"
                        />
                        <span>{{ languages.label(subpanel.label, $route.params.module) }}</span>
                        <span
                            v-if="subpanel.records?.length"
                            class="mint-subpanel-records-count"
                            v-text="subpanel.records.length"
                        />
                    </div>
                    <MintPanelSubpanelsButtons
                        :module="$route.params.module"
                        :subpanel="subpanel"
                        :isExpanded="expandedSubpanels.includes(subpanel.key)"
                    />
                </v-expansion-panel-title>
                <v-expansion-panel-text class="mint-subpanel-content">
                    <MintDataTable :columns="subpanel.columns" :records="subpanel.records ?? []" />
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import MintDataTable from '@/components/MintDataTable/MintDataTable.vue'
import MintPanelSubpanelsButtons from './MintPanelSubpanelsButtons.vue'

onMounted(() => {
    store.fetchLanguagesForSubpanels()
    store.fetchSubpanelsData()
})

const store = useRecordViewStore()
const languages = useLanguagesStore()
const expandedSubpanels = ref<string[]>([])
</script>

<style scoped lang="scss">
.subpanels-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 24px #0099761a;
    transition: all 100ms ease-in-out;

    h1 {
        font-size: 20px;
        padding: 16px 16px;
        border-bottom: 1px solid #dbdbdb;
    }
}
.subpanels-accordion {
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }
}

.mint-subpanel {
    .mint-subpanel-title {
        color: rgb(var(--v-theme-secondary));
        font-size: 15px;
        font-weight: 600;
        padding: 16px;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        padding: 12px 16px;

        > div {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mint-subpanel-records-count {
            background: rgb(var(--v-theme-primary));
            color: rgb(var(--v-theme-primary-lighter));
            font-size: 12px;
            letter-spacing: 0.11px;
            font-weight: 400;
            border-radius: 50%;
            padding: 1px 1px 2px 1px;
            min-width: 18px;
            aspect-ratio: 1/1;
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
        }
    }
    .mint-subpanel-content {
        :deep(.v-expansion-panel-text__wrapper) {
            padding: 0px;
            margin-bottom: 48px;
        }
    }
}

.mint-subpanel-disabled {
    pointer-events: none;
    &:hover {
        background: inherit;
    }

    .mint-subpanel-title {
        color: #0004;
    }

    .mint-subpanel-create-btn {
        pointer-events: all;
    }
}
</style>
