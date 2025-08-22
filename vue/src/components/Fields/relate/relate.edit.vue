<template>
    <v-menu v-model="menuOpen" :location="'bottom'">
        <template v-slot:activator="val">
            <v-text-field
                :label="props.label"
                variant="outlined"
                density="compact"
                hide-details
                :error="props.state === 'error'"
                v-model="model.name"
                v-bind="{ ...$attrs, ...val.props }"
                @input="(event) => fetchItems(event)"
                @click="menuOpen = false"
            >
                <template #append-inner>
                    <v-fab-transition class="search-prepend-icon">
                        <v-icon v-if="model.name" icon="mdi-close" @click="model = { id: '', name: '' }" />
                        <v-icon v-else icon="mdi-magnify" />
                    </v-fab-transition>
                </template>
            </v-text-field>
        </template>
        <v-list>
            <v-list-item v-if="isLoading">
                <v-progress-circular color="primary" indeterminate></v-progress-circular>
            </v-list-item>
            <v-list-item
                v-if="!isLoading && !items.length"
                class="text-caption"
                v-text="
                    !items || model.name.length < 3
                        ? languages.label('LBL_MINT4_GS_HELP_TIP')
                        : languages.label('LBL_MINT4_GS_NO_RECORDS_FOUND')
                "
                @input="(event) => fetchItems(event, index)"
            />
            <div v-if="!isLoading">
                <v-list-item @click="clickOnMenuItem(item)" v-for="(item, index) in items" :key="index">
                    <span v-html="getHighlightedText(item.name, model.name)"></span>
                </v-list-item>
            </div>
            <v-divider />
            <v-list-item>
                <MintButton
                    variant="text"
                    :text="languages.label('LBL_ADVANCED_SEARCH_BUTTON')"
                    @click="openRelatePopup"
                    icon="mdi-text-search"
                    color="primary"
                />
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useModulesStore } from '@/store/modules'
import { usePopupsStore } from '@/store/popups'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { modulesApi } from '@/api/modules.api'
import he from 'he'
import { FieldProps } from '../Field.model'
import getFilters from '@/utils/qsOperators'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])

let debounceTimeout: number | null = null
const DEBOUNCE_TIME = 500

const languages = useLanguagesStore()
const popupsStore = usePopupsStore()
const modulesStore = useModulesStore()
const menuOpen = ref(false)
const items = ref(
    props.data.bean.attributes[props.defs.id_name]
        ? [
              {
                  id: props.data.bean.attributes[props.defs.id_name],
                  name: props.modelValue,
              },
          ]
        : [],
)

const currentItem = ref({ id: props.data.bean.attributes[props.defs.id_name], name: props.data.bean.attributes[props.defs.name] })
const isLoading = ref(false)
const model = computed({
    get() {
        return currentItem.value
    },
    set(newVal) {
        currentItem.value = newVal
        emit('update:modelValue', newVal.name, { [props.defs.id_name]: newVal.id })
    },
})

async function fetchItems(e) {
    if (model.value?.name.length >= 3) {
        items.value = []
        isLoading.value = true
        menuOpen.value = true
        const val = e?.target?.value ?? props.data.bean.attributes[props.defs.name] ?? ''
        const predefinedFilters = getFilters(
            modulesStore.modules[props.defs.module].vardefs,
            Array.isArray(props.defs.filters) ? props.defs.filters : [],
        )
        const filters = {
            ...predefinedFilters,
                must: [
                ...(predefinedFilters.must || []),
                    {
                        wildcard: {
                            name: val + '*',
                        },
                    },
                ],
            }
        if (debounceTimeout) {
            clearTimeout(debounceTimeout)
        }
        debounceTimeout = window.setTimeout(async () => {
            const response = await modulesApi.getListData(
                props.defs.module,
                '',
                filters,
                0,
                100,
                false,
                props.defs.rname ?? 'name',
                'asc',
            )
            items.value = response.data.results
            isLoading.value = false
        }, DEBOUNCE_TIME)
    } else {
        items.value = []
    }
}

function openRelatePopup() {
    popupsStore.showPopup({
        component: MintPopupRelate,
        title: useLanguagesStore().translateListValue(props.defs.module, 'moduleList'),
        icon: 'mdi-view-list',
        data: {
            moduleName: props.defs.module,
            popupMode: 'single',
            fieldToNameArray: { id: props.defs.id_name, name: props.defs.name },
            filterDefs: Array.isArray(props.defs.filters) ? props.defs.filters : [],
            onConfirm: (data: string | string[]) => {
                model.value = {
                    id: data.nameToValueArray[props.defs.id_name],
                    name: data.nameToValueArray[props.defs.name],
                }
            },
            onClose: () => {},
        },
    })
}

function clickOnMenuItem(item) {
    model.value = {
        id: item.id,
        name: item.name,
    }
}

function getHighlightedText(text: string, query: string) {
    query = he.encode(query)
    text = he.encode(text)
    if (!query) {
        return text
    }
    try {
        const regex = new RegExp(
            `\\b(${query
                .split(' ')
                .filter((q) => !['AND', 'OR'].includes(q) && q.length > 2)
                .join('|')})`,
            'gi',
        )
        text = text.replace(regex, '<span class="highlighted">$&</span>')
        return text
    } catch {
        return text
    }
}
</script>

<style scoped lang="scss">
.v-list-item {
    :deep(.highlighted) {
        font-weight: 600;
        color: rgb(var(--v-theme-primary));
        background: rgb(var(--v-theme-primary-light));
    }
}
</style>
