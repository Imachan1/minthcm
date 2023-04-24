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
                <v-btn
                    v-bind="props"
                    size="small"
                    density="comfortable"
                    color="secondary"
                    icon="mdi-plus"
                    :variant="isActive ? 'elevated' : 'tonal'"
                />
            </template>
            <MintMenuList :items="backend.quickCreate" />
            <!-- <v-list nav color="secondary" density="compact" class="menu-list">
                <v-list-item
                    v-for="qc in backend.quickCreate"
                    :key="qc.module"
                    :to="`/${qc.module}/EditView`"
                >
                    <template #prepend>
                        <v-icon size="16" icon="mdi-pencil" />
                    </template>
                    <v-list-item-title>
                        {{ qc.module }}
                    </v-list-item-title>
                </v-list-item>
            </v-list> -->
        </v-menu>

        <v-menu offset="16" :close-on-content-click="false">
            <template v-slot:activator="{ props, isActive }">
                <v-badge
                    v-bind="props"
                    :content="alerts.unreadAlertsCount"
                    color="error"
                    location="bottom end"
                    :model-value="alerts.unreadAlertsCount > 0"
                >
                    <v-btn
                        size="small"
                        density="comfortable"
                        color="secondary"
                        icon="mdi-bell"
                        :variant="isActive ? 'elevated' : 'tonal'"
                    >
                    </v-btn>
                </v-badge>
            </template>
            <DefaultLayoutAlerts />
        </v-menu>
        <v-menu offset="16">
            <template v-slot:activator="{ props, isActive }">
                <v-btn
                    class="user-btn"
                    size="small"
                    v-bind="props"
                    rounded="xl"
                    color="secondary"
                    :variant="isActive ? 'elevated' : 'tonal'"
                >
                    <template #default>
                        {{ auth.user?.first_name || auth.user?.last_name }}
                    </template>
                    <template #prepend>
                        <v-icon size="20" icon="mdi-account" />
                    </template>
                </v-btn>
            </template>
            <v-list nav color="secondary" density="compact" class="menu-list">
                <v-list-item to="/user">
                    <template #prepend>
                        <v-icon size="16" icon="mdi-account" />
                    </template>
                    <v-list-item-title> Profile </v-list-item-title>
                </v-list-item>
                <v-list-item to="/Administration">
                    <template #prepend>
                        <v-icon size="16" icon="mdi-cog" />
                    </template>
                    <v-list-item-title> Administration </v-list-item-title>
                </v-list-item>
                <v-list-item to="/Users/Logout">
                    <template #prepend>
                        <v-icon size="16" icon="mdi-logout" />
                    </template>
                    <v-list-item-title> Logout </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </nav>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'
import { useAlertsStore } from '@/store/alerts'
import DefaultLayoutAlerts from './DefaultLayoutAlerts.vue'
import MintMenuList from '@/components/MintMenuList.vue'

const router = useRouter()
const auth = useAuthStore()
const alerts = useAlertsStore()
const backend = useBackendStore()

const initialQuery = new URL(location.href).searchParams.get('query_string')
const searchQuery = ref(initialQuery ?? '')
const standardizedQuery = computed(() => {
    return searchQuery.value.trim()
})

function search() {
    if (standardizedQuery.value) {
        router.push(
            `/Home/UnifiedSearch?search_form=false&query_string=${standardizedQuery.value}`,
        )
    }
}
</script>

<style scoped lang="scss">
.top-bar {
    z-index: 1990;
    position: fixed;
    height: 72px;
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
    background: rgb(var(--v-theme-primary));
    min-height: 72px;
    height: 72px;
    width: 260px;
    z-index: 1000;
    img {
        padding: 12px 16px;
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

.user-btn {
    text-transform: capitalize;

    .v-icon {
    }
}
</style>
