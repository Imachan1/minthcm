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
        <MintMenuList :items="menuItems" />
    </v-menu>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'

const auth = useAuthStore()
const languages = useLanguagesStore()

const menuItems = computed<MenuListItem[]>(() => {
    const items: MenuListItem[] = []
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_PROFILE'),
        icon: 'account',
        url: `/modules/Employees/DetailView/${auth.user?.id}`,
    })
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_SETTINGS'),
        icon: 'account-settings',
        url: `/modules/Users/EditView/${auth.user?.id}`,
    })
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_EMPLOYEES'),
        icon: 'account-group',
        url: '/modules/Employees',
    })
    if (auth.user?.is_admin) {
        items.push({
            title: languages.label('LBL_MINT4_USER_MENU_ADMINISTRATION'),
            icon: 'cog',
            url: '/modules/Administration',
        })
    }
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_SUPPORT'),
        icon: 'face-agent',
        onClick: () => window.open('https://minthcm.org/support/', '_blank'),
    })
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_ABOUT'),
        icon: 'information',
        url: '/modules/Home/About',
    })
    items.push({
        title: languages.label('LBL_MINT4_USER_MENU_LOGOUT'),
        icon: 'logout',
        onClick: () => auth.logout(),
    })
    return items
})
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
