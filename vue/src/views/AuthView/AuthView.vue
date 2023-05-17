<template>
    <div class="auth-view">
        <img src="../../assets/mint_logo.png" height="32" />
        <v-slide-x-transition hide-on-leave class="form-content">
            <AuthViewLogin
                v-if="form === 'login'"
                v-model:username="username"
                @update:username="(newVal) => (username = newVal)"
            />
            <AuthViewForget v-else-if="form === 'forget'" />
            <AuthViewReset v-else-if="form === 'reset'" />
        </v-slide-x-transition>
        <div class="auth-footer">
            <v-slide-x-transition hide-on-leave>
                <div
                    v-if="form === 'login'"
                    @click="form = 'forget'"
                    v-text="languages.label('LBL_MINT4_AUTH_FORGET_PASSWORD_QUESTION')"
                />
                <div v-else @click="form = 'login'">← {{ languages.label('LBL_MINT4_AUTH_BACK_TO_LOGIN') }}</div>
            </v-slide-x-transition>
            <v-menu offset="16">
                <template v-slot:activator="{ props, isActive }">
                    <MintButton
                        v-bind="props"
                        variant="nav"
                        icon="mdi-translate"
                        :active="isActive"
                        :tooltip="languages.label('LBL_MINT4_AUTH_LANG_TOOLTIP')"
                    />
                </template>
                <MintMenuList
                    :items="[
                        { title: 'English', icon: 'fi-gb', onClick: () => {} },
                        { title: 'polski', icon: 'fi-pl', onClick: () => {} },
                    ]"
                />
            </v-menu>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButton.vue'
import MintMenuList from '@/components/MintMenuList.vue'
import AuthViewLogin from './AuthViewLogin.vue'
import AuthViewForget from './AuthViewForget.vue'
import AuthViewReset from './AuthViewReset.vue'

const languages = useLanguagesStore()

const form = ref<'login' | 'forget' | 'reset'>('login')
const username = ref('')
</script>

<style scoped lang="scss">
.auth-view {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    width: 100%;
    max-width: 457px;
    margin: 0px auto;
    padding: 64px 64px 32px 64px;
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-items: center;
    justify-content: center;

    .form-content {
        width: 100%;
        margin-top: 32px;
        display: flex;
        text-align: center;
        flex-direction: column;
        justify-content: center;
        gap: 32px;
    }

    .auth-footer {
        width: 100%;
        margin-top: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));

        div {
            cursor: pointer;
            user-select: none;
            &:hover {
                color: rgb(var(--v-theme-secondary-dark));
            }
        }
    }
}
</style>

<style>
/* fix chrome autofill background change */
/* todo: autfill bug */
.login-container .login-input input:-webkit-autofill,
.login-container .login-input input:-webkit-autofill:hover,
.login-container .login-input input:-webkit-autofill:focus,
.login-container .login-input input:-webkit-autofill:active {
    transition: background-color 9999s ease-in-out 0s;
}
</style>
