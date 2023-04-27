<template>
    <v-menu offset="16">
        <template v-slot:activator="{ props, isActive }">
            <button
                class="user-btn"
                :class="[isActive && 'active']"
                v-ripple
                v-bind="props"
            >
                <v-icon size="24" icon="mdi-account" />
                <span>
                    {{ auth.user?.first_name || auth.user?.last_name }}
                </span>
            </button>
        </template>
        <v-list nav color="secondary" density="compact" class="menu-list">
            <v-list-item :to="`/Employees/DetailView/${auth.user?.id}`">
                <template #prepend>
                    <v-icon size="16" icon="mdi-account" />
                </template>
                <v-list-item-title> Profile </v-list-item-title>
            </v-list-item>
            <v-list-item :to="`/Users/EditView/${auth.user?.id}`">
                <template #prepend>
                    <v-icon size="16" icon="mdi-account-settings" />
                </template>
                <v-list-item-title> Settings </v-list-item-title>
            </v-list-item>
            <v-list-item to="/Employees">
                <template #prepend>
                    <v-icon size="16" icon="mdi-account-group" />
                </template>
                <v-list-item-title> Employees </v-list-item-title>
            </v-list-item>
            <v-list-item v-if="auth.user?.is_admin" to="/Administration">
                <template #prepend>
                    <v-icon size="16" icon="mdi-cog" />
                </template>
                <v-list-item-title> Administration </v-list-item-title>
            </v-list-item>
            <v-list-item tag="a" href="https://minthcm.org/support/" target="_blank">
                <template #prepend>
                    <v-icon size="16" icon="mdi-face-agent" />
                </template>
                <v-list-item-title> Support </v-list-item-title>
            </v-list-item>
            <v-list-item to="/Home/About">
                <template #prepend>
                    <v-icon size="16" icon="mdi-information" />
                </template>
                <v-list-item-title> About </v-list-item-title>
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

<style scoped lang="scss">
.user-btn {
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 0.43px;
    transition: all 150ms ease-in-out;
    text-transform: capitalize;
    color: rgb(var(--v-theme-secondary));
    background: #f5fbfa;
    border-radius: 50px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;

    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: #e0ece9;
    }

    &.active {
        color: #f5fbfa;
        background: rgb(var(--v-theme-secondary));
    }
}

</style>
