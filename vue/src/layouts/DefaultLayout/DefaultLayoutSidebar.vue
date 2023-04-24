<template>
    <v-navigation-drawer
        class="drawer-nav"
        expand-on-hover
        :rail="$vuetify.display.mdAndDown"
        permanent
        width="260"
        color="#d5e6e4dd"
        floating
    >
        <v-list
            v-if="backend.activeModule?.label !== 'Home' && backend.activeModule?.actions"
            nav
            bg-color="primary"
            class="nav-list flex-shrink-0"
            density="comfortable"
        >
            <v-list-item
                v-for="action in backend.activeModule.actions"
                :key="action.action"
                class="nav-item"
                :prepend-icon="`mdi-${action.icon}`"
                :value="action.action"
                :to="action.url"
                :active="false"
            >
                <v-list-item-title class="nav-title">
                    {{ action.name }}
                </v-list-item-title>
                <template v-if="action.options" #append>
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-btn
                                v-bind="props"
                                @click.prevent.stop="null"
                                class="menu-icon"
                                icon="mdi-dots-vertical"
                                variant="text"
                                density="compact"
                            />
                        </template>
                        <v-list class="menu-list">
                            <v-list-item title="Action 1" />
                            <v-list-item title="Action 2" />
                            <v-list-item title="Action 3" />
                            <v-list-item title="Action 4" />
                        </v-list>
                    </v-menu>
                </template>
            </v-list-item>
        </v-list>
        <v-text-field
            v-model="filterModulesQuery"
            class="find-module"
            placeholder="Find module..."
            variant="plain"
            density="compact"
            hide-details
        >
            <template #prepend-inner>
                <v-icon icon="mdi-magnify" />
            </template>
        </v-text-field>
        <v-list nav class="nav-list nav-list-blurred flex-grow-1">
            <transition-group name="list" tag="ul">
                <template v-if="filteredModules.length">
                    <v-list-item
                        class="nav-item"
                        v-for="filteredModule in filteredModules"
                        :key="filteredModule.label"
                        :value="filteredModule.label"
                        :to="`/${filteredModule.label}`"
                        :active="filteredModule.label === url.module"
                        color="secondary"
                    >
                        <template #prepend>
                            <v-icon :icon="`mdi-${filteredModule.icon}`" />
                        </template>
                        <v-list-item-title>
                            {{ filteredModule.name }}
                        </v-list-item-title>
                        <template #append v-if="filteredModule.label !== 'Home'">
                            <v-menu>
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        @click.prevent.stop="null"
                                        class="menu-icon"
                                        icon="mdi-dots-vertical"
                                        variant="text"
                                        density="compact"
                                        color="secondary"
                                    />
                                </template>
                                <v-list
                                    nav
                                    class="menu-list"
                                    color="secondary"
                                    density="compact"
                                >
                                    <v-list-item
                                        v-for="action in filteredModule.actions"
                                        :key="action.action"
                                        :to="action.url"
                                        :active="false"
                                    >
                                        <template #prepend>
                                            <v-icon size="16" :icon="`mdi-${action.icon}`" />
                                        </template>
                                        <v-list-item-title>
                                            {{ action.name }}
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </v-list-item>
                </template>
                <div class="px-4" v-else>No modules found</div>
            </transition-group>
        </v-list>
        <v-expansion-panels class="nav-accordion" variant="accordion">
            <v-expansion-panel bg-color="transparent">
                <v-expansion-panel-title>
                    <v-icon class="mr-4">mdi-history</v-icon>
                    <span>Recently viewed</span>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-list nav class="nav-list" density="compact">
                        <v-list-item
                            v-for="recent in recents.recents"
                            :key="recent.item_id"
                            prepend-icon="mdi-clock"
                            :title="recent.item_summary"
                            :value="recent.item_id"
                            :to="`/${recent.module_name}/DetailView/${recent.item_id}`"
                            :active="false"
                        />
                    </v-list>
                </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel bg-color="transparent" elevetion="10">
                <v-expansion-panel-title>
                    <v-icon class="mr-4">mdi-heart</v-icon>
                    <span>Favorite records</span>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-list nav class="nav-list" density="compact">
                        <v-list-item
                            v-for="favorite in favorites.favorites"
                            :key="favorite.id"
                            prepend-icon="mdi-heart"
                            :title="favorite.item_summary"
                            :value="favorite.id"
                            :to="`/${favorite.module_name}/DetailView/${favorite.id}`"
                            :active="false"
                        />
                    </v-list>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useUrlStore } from '@/store/url'
import { useFavoritesStore } from '@/store/favorites'
import { useRecentsStore } from '@/store/recents'

const backend = useBackendStore()
const url = useUrlStore()
const favorites = useFavoritesStore()
const recents = useRecentsStore()

const filterModulesQuery = ref('')
const filteredModules = computed(() => {
    const query = filterModulesQuery.value.trim()
    if (!query) {
        return backend.modules
    }
    return backend.modules.filter((m) => m.name.toLowerCase().includes(query))
})
</script>
<style lang="scss">
.drawer-nav {
    top: 72px !important;
    max-height: calc(100vh - 72px);
    backdrop-filter: blur(10px);
    .v-navigation-drawer__content {
        display: flex;
        flex-direction: column;
    }
}
</style>
<style scoped lang="scss">
.nav-list {
    overflow-y: auto !important;
    -ms-overflow-style: none;
    scrollbar-width: none;
    &::-webkit-scrollbar {
        display: none;
    }
    :deep(.v-list-item__prepend .v-icon) {
        margin-inline-end: 16px;
    }
}

.find-module {
    padding: 12px 16px 12px 16px;
    flex: 0;
    color: rgb(var(--v-theme-secondary));
    .v-icon {
        opacity: 1;
    }
    :deep(.v-field__input) {
        padding-top: 8px;
    }
}
.nav-accordion {
    white-space: nowrap;
    box-shadow: 0 0 1rem #0003;
    color: rgb(var(--v-theme-secondary));
    font-weight: 600;
    :deep(.v-expansion-panel) {
        border-radius: 0px;
    }
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }
    :deep(.v-expansion-panel-text__wrapper) {
        padding: 0px;
    }
    :deep(.v-expansion-panel-title) {
        padding-left: 16px;
        padding-right: 16px;
    }
    :deep(.v-list-item-title) {
        font-weight: 600;
    }
}

.nav-item {
    transition: all 300ms ease-out;
    border-radius: 0px 20px 20px 0px;
    &:hover {
        transform: translateX(-8px);
        background: #0000001f;
    }
    &:hover .nav-title {
        color: white;
    }
    .menu-icon {
        opacity: 0 !important;
        transition: all 250ms ease-in-out;
    }
    &:hover .menu-icon {
        opacity: 1 !important;
    }
}

.nav-title {
    transition: all 150ms ease-in-out;
    color: #ffffffaf;
    font-weight: 600;
    font-size: 16px;
}

.nav-list-blurred {
    .v-list-item-title {
        font-size: 1rem;
        font-weight: 600;
        color: rgb(var(--v-theme-secondary));
        line-height: 1.5;
    }
    .v-icon {
        color: rgb(var(--v-theme-secondary));
        opacity: 1;
    }
}
</style>
