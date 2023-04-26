<template>
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
            <v-list-item :to="`/Employees/DetailView/${auth.user?.id}`">
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
            <v-list-item @click="auth.logout">
                <template #prepend>
                    <v-icon size="16" icon="mdi-logout" />
                </template>
                <v-list-item-title> Logout </v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/store/auth'

const auth = useAuthStore()
</script>

<style scoped lang="scss"></style>
