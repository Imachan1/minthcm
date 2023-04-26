<template>
    <div class="login-view">
        <img src="../../assets/mint_logo.png" />
        <v-form
            ref="form"
            @submit.prevent="handleSubmit"
            class="login-container"
        >
            <v-text-field
                class="login-input"
                v-model="username"
                name="user_name"
                density="comfortable"
                placeholder="Username"
                prepend-inner-icon="mdi-account"
                :error-messages="usernameError"
                hide-details="auto"
                :disabled="isSubmiting"
                :rules="[(v) => !!v || 'Pole jest wymagane']"
                required
            />
            <v-text-field
                class="login-input"
                v-model="password"
                type="password"
                name="username_password"
                density="comfortable"
                placeholder="Password"
                prepend-inner-icon="mdi-lock"
                hide-details="auto"
                :disabled="isSubmiting"
                :rules="[(v) => !!v || 'Pole jest wymagane']"
                required
            />
            <v-btn type="submit" color="primary" :loading="isSubmiting">
                {{ backend.label('LBL_LOGIN_BUTTON_TITLE', 'Users') }}
            </v-btn>
        </v-form>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios'
import { ref } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'

const backend = useBackendStore()
const auth = useAuthStore()

const form = ref<HTMLFormElement | null>(null)
const username = ref('')
const usernameError = ref('')
const password = ref('')
const isSubmiting = ref(false)

async function handleSubmit() {
    if (isSubmiting.value) {
        return
    }
    isSubmiting.value = true
    const { valid } = await form.value?.validate()
    if (!valid) {
        isSubmiting.value = false
        return
    }
    await auth.authenticate(username.value, password.value)
    await backend.init()
    if (!auth.user?.id) {
        usernameError.value = 'Niepoprawny login lub hasło'
    }
    isSubmiting.value = false
}
</script>

<style scoped lang="scss">
.login-view {
    background: #fff;
    min-height: calc(100vh - 112px);
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-items: center;
    justify-content: center;

    .login-container {
        width: 400px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
}
</style>

<style>
/* fix chrome autofill background change */
.login-container .login-input input:-webkit-autofill,
.login-container .login-input input:-webkit-autofill:hover,
.login-container .login-input input:-webkit-autofill:focus,
.login-container .login-input input:-webkit-autofill:active {
    transition: background-color 9999s ease-in-out 0s;
}
</style>
