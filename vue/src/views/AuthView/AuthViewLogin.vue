<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_LOGIN_TITLE')" />
        <MintStatusBox v-if="loginError" type="error">{{
            languages.label('LBL_MINT4_AUTH_LOGIN_ERROR')
        }}</MintStatusBox>
        <v-form class="login-form" @submit.prevent="handleSubmit">
            <v-text-field
                class="login-input"
                v-model="authViewStore.username"
                color="primary"
                name="username"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
            />
            <v-text-field
                class="login-input"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                name="password"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_PASSWORD')"
                variant="outlined"
                hide-details
                :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="showPassword = !showPassword"
                :disabled="isSubmiting"
            />
            <MintButton variant="primary" :text="languages.label('LBL_MINT4_AUTH_LOGIN_BTN')" @click="handleSubmit" />
        </v-form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthViewStore } from './AuthViewStore'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import { useRouter } from 'vue-router'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'

const authViewStore = useAuthViewStore()
const backend = useBackendStore()
const languages = useLanguagesStore()
const auth = useAuthStore()

onMounted(() => {
    authViewStore.footerNavAction = {
        routeName: 'auth-forget',
        label: languages.label('LBL_MINT4_AUTH_FORGET_PASSWORD_QUESTION'),
    }
})

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const isSubmiting = ref(false)
const loginError = ref(false)
const router = useRouter()

async function handleSubmit() {
    loginError.value = false
    if (isSubmiting.value) {
        return
    }
    isSubmiting.value = true
    await auth.authenticate(authViewStore.username, password.value)
    backend.initialLoading = true
    await backend.init()
    if (!auth.user?.id) {
        loginError.value = true
    } else if (auth.user.show_login_wizard) {
        router.push({ name: 'setup-wizard' })
    } else {
        router.push({ name: 'dashboard' })
    }
    isSubmiting.value = false
}
</script>

<style scoped lang="scss">
.login-form {
    display: flex;
    flex-direction: column;
    gap: 32px;
    width: 100%;
}
</style>
<style>
.login-form .login-input input:-webkit-autofill,
.login-form .login-input input:-webkit-autofill:hover,
.login-form .login-input input:-webkit-autofill:focus,
.login-form .login-input input:-webkit-autofill:active {
    transition: background-color 9999s ease-in-out 0s;
}
</style>
