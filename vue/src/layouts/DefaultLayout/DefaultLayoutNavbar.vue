<template>
    <nav class="navbar elevation-1">
        <router-link to="/">
            <v-icon size="32">mdi-home</v-icon>
        </router-link>
        <v-menu
            open-on-hover
            v-for="tab in backend.tabs"
            :key="tab.label"
            max-height="calc(100vh - 60px)"
        >
            <template #activator="{ props }">
                <span v-text="tab.label" v-bind="props" />
            </template>
            <router-link
                style="
                    background: rgb(var(--v-theme-primary));
                    color: white;
                    padding: 8px 16px;
                "
                v-for="(moduleName, moduleKey) in tab.modules"
                :key="moduleKey"
                v-text="moduleName"
                :to="`/${moduleKey}`"
            />
        </v-menu>
        <v-spacer />
        <v-badge content="2" color="error">
            <v-icon size="32">mdi-bell</v-icon>
        </v-badge>
        <router-link to="/user">
            <v-icon size="32">mdi-account</v-icon>
            <span>Michał</span>
        </router-link>
        <router-link :to="{ path: '/Administration', force: true }">
            <v-icon size="32">mdi-cog</v-icon>
        </router-link>
        <router-link to="/Users/Logout">
            <v-icon size="32">mdi-logout</v-icon>
        </router-link>
    </nav>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'

const backend = useBackendStore()
</script>

<style scoped lang="scss">
.navbar {
    z-index: 1005;
    position: fixed;
    top: 0px;
    width: 100%;
    color: white;
    background: rgb(var(--v-theme-primary));
    height: 60px;
    display: flex;
    padding: 0 16px;
    gap: 16px;
    align-items: center;
}
</style>
