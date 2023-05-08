<template>
    <nav class="top-bar">
        <router-link class="img-logo" to="/">
            <img src="../../assets/mint_logo_white.svg" />
        </router-link>
        <div style="width: 40ch">
            <v-text-field
                v-model="searchQuery"
                class="search-field"
                hide-details
                placeholder="Search..."
                @keyup.enter="search"
                prepend-inner-icon="mdi-magnify"
                variant="plain"
                clearable
            />
        </div>
        <v-slide-x-transition>
            <v-btn
                v-if="searchQuery?.length"
                @click="search"
                icon="mdi-chevron-right"
                size="default"
                density="comfortable"
                variant="text"
                color="secondary"
            />
        </v-slide-x-transition>
        <v-spacer />
        <v-menu offset="16">
            <template v-slot:activator="{ props, isActive }">
                <MintButton v-bind="props" variant="nav" icon="mdi-plus" :active="isActive" />
            </template>
            <MintMenuList :items="backend.quickCreate" />
        </v-menu>

        <MintButton icon="mdi-apps" variant="nav" @click="showModulesPopup"
        :active="!!popups.popups.find(p => p.component === DefaultLayoutModulesPopup)" />

        <v-menu offset="16" :close-on-content-click="false">
            <template v-slot:activator="{ props, isActive }">
                <v-badge
                    v-bind="props"
                    :content="alerts.unreadAlertsCount"
                    color="error"
                    location="bottom end"
                    :model-value="alerts.unreadAlertsCount > 0"
                >
                    <MintButton icon="mdi-bell" variant="nav" :active="isActive" />
                </v-badge>
            </template>
            <DefaultLayoutAlerts />
        </v-menu>
        <DefaultLayoutUser />
    </nav>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAlertsStore } from '@/store/alerts'
import { usePopupsStore } from '@/store/popups'
import DefaultLayoutAlerts from './DefaultLayoutAlerts.vue'
import DefaultLayoutUser from './DefaultLayoutUser.vue'
import MintMenuList from '@/components/MintMenuList.vue'
import MintButton from '@/components/MintButton.vue'
import DefaultLayoutModulesPopup from './DefaultLayoutModulesPopup.vue'

const router = useRouter()
const alerts = useAlertsStore()
const backend = useBackendStore()
const popups = usePopupsStore()

const initialQuery = new URL(location.href).searchParams.get('query_string')
const searchQuery = ref<string | null>(initialQuery ?? '')
const standardizedQuery = computed(() => {
    return searchQuery.value?.trim()
})

function search() {
    if (standardizedQuery.value) {
        router.push(
            `/Home/UnifiedSearch?search_form=false&query_string=${standardizedQuery.value}`,
        )
    }
}

function showModulesPopup() {
    popups.showPopup({
        title: 'All modules',
        icon: 'mdi-apps',
        component: DefaultLayoutModulesPopup,
    })
}
</script>

<style scoped lang="scss">
.top-bar {
    z-index: 1990;
    position: fixed;
    height: var(--v-top-nav-height);
    background: rgb(var(--v-theme-surface));
    width: 100%;
    display: flex;
    gap: 16px;
    align-items: center;
    box-shadow: 0 0 1rem #0005;
    padding-right: 16px;
}
.nav-btn {
    padding: 6px;
}
.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
    }
}
.img-logo {
    display: flex;
    align-items: center;
    background: rgb(var(--v-theme-primary));
    min-height: var(--v-top-nav-height);
    height: var(--v-top-nav-height);
    width: 260px;
    z-index: 1000;
    img {
        padding: 12px 24px;
        height: 58px;
    }
}
.search-field {
    flex-grow: 1;
    :deep(.v-field__prepend-inner) {
        padding-top: 12px;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__clearable) {
        padding-top: 12px;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__input) {
        padding-top: 0px;
    }
    :deep(.v-icon) {
        opacity: 1;
    }
}

</style>
