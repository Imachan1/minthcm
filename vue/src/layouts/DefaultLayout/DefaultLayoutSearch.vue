<template>
    <div class="search-container">
        <v-text-field
            v-model="searchQuery"
            class="search-input"
            :class="[isFocused && 'search-input-active']"
            hide-details
            placeholder="Search..."
            @keyup.enter="search"
            variant="plain"
            @update:focused="isFocused = $event"
        >
            <template #prepend-inner>
                <v-fab-transition class="search-prepend-icon">
                    <v-icon v-if="standardizedQuery" icon="mdi-close" @click="searchQuery = ''" />
                    <v-icon v-else icon="mdi-magnify" />
                </v-fab-transition>
            </template>
        </v-text-field>
        <v-slide-y-transition>
            <div v-if="isFocused && response?.results?.length" class="search-results">
                <div
                    v-for="result in response.results"
                    :key="result.id"
                    color="primary"
                    v-ripple="{ class: 'text-primary' }"
                    @click="showRecord(result.module, result.id)"
                    class="search-result"
                >
                    <v-icon :icon="getModuleIcon(result.module)" color="primary" />
                    <div>
                        <span v-html="getHighlightedText(result.name, response.query)" />
                        <div class="search-result-description">
                            <span v-text="result.module" />
                            <span v-text="`${result.meta?.label}: ${result.meta?.value}`" />
                        </div>
                    </div>
                </div>
                <div class="search-results-footer">
                    <span @click="search" v-text="'Display all records in a list view'" />
                </div>
            </div>
        </v-slide-y-transition>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import he from 'he'

const { getModuleIcon } = useBackendStore()
const router = useRouter()
const isFocused = ref(false)
const initialQuery = new URL(location.href).searchParams.get('query_string')
const searchQuery = ref<string | null>(initialQuery ?? '')
const standardizedQuery = computed(() => {
    return searchQuery.value?.trim()
})

function showRecord(module: string, id: string) {
    if (module && id) {
        router.push(`/${module}/DetailView/${id}`)
    }
}

const response = {
    query: 'Smi',
    total: 2, // jeśli nie mamy możliwości obliczenia total, to może być też flaga overflow
    results: [
        // jeśli total > results.length, to znaczy, że jest overflow
        {
            name: 'Kevin Smith',
            module: 'Candidates',
            id: '1',
            meta: {
                label: 'email', // przetłumaczone na backendzie?
                // jeśli ma być tłumaczone na froncie to musiałbym strzelić po języki wszystkich modułów zawartych w results
                value: 'kevin.smith@gmail.com',
            },
        },
        {
            name: 'Smithsonian Institute',
            module: 'Meetings',
            id: '2',
            meta: {
                label: 'start time',
                value: '05.05.2023 10:30', // parsowanie daty na backendzie?
                // jeśli parsowanie ma być na froncie, to trzeba do meta wysyłać jeszcze type
            },
        },
    ],
}

function search() {
    if (standardizedQuery.value) {
        router.push(`/Home/UnifiedSearch?search_form=false&query_string=${standardizedQuery.value}`)
    }
}

function getHighlightedText(text: string, query: string) {
    text = he.encode(text)
    if (!query) {
        return text
    }
    return text?.replace(new RegExp(query, 'gi'), '<span class="highlighted">$&</span>') || text
}
</script>

<style scoped lang="scss">
.search-container {
    position: relative;
    flex-grow: 1;
    max-width: 50ch;
}

.search-input {
    position: relative;
    background: rgb(var(--v-theme-surface));
    z-index: 1;
    border-radius: 100px;
    transition: all 100ms ease-in-out;
    .search-prepend-icon {
        margin: 0px 10px;
        top: -4px;
        opacity: 1;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__input) {
        padding-top: 0px;
    }

    &-active {
        background: rgb(var(--v-theme-primary-light));
    }
}


.search-results {
    position: absolute;
    padding: 48px 0px 16px 0px;
    width: 100%;
    left: 0px;
    top: 20px;
    background: rgb(var(--v-theme-surface));
    border-radius: 0px 0px 4px 4px;
    box-shadow: 0px 3px 6px #00000029;
    .search-results-footer {
        margin-top: 8px;
        text-align: center;
        span {
            font-size: 12px;
            color: rgb(var(--v-theme-secondary));
            text-decoration: underline;
            cursor: pointer;
        }
    }
}

.search-result {
    width: 100%;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 200ms ease-in-out;
    cursor: pointer;
    &:hover {
        background: rgb(var(--v-theme-primary-light));
    }

    :deep(.highlighted) {
        font-weight: 600;
        color: rgb(var(--v-theme-primary));
        background: rgb(var(--v-theme-primary-light));
    }

    .search-result-description {
        display: flex;
        gap: 32px;
        font-size: 12px;
        opacity: 0.7;
    }
}
</style>
