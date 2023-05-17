<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_LOGIN_TITLE')" />
        <MintStatusBox v-if="loginError" type="error">{{
            languages.label('LBL_MINT4_AUTH_LOGIN_ERROR')
        }}</MintStatusBox>
        <v-text-field
            v-model="username"
            color="primary"
            base-color="#00000099"
            density="comfortable"
            :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
            variant="outlined"
            hide-details
            :disabled="isSubmiting"
        />
        <v-text-field
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
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
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import MintButton from '@/components/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'

const backend = useBackendStore()
const languages = useLanguagesStore()
const auth = useAuthStore()

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const isSubmiting = ref(false)
const loginError = ref(false)

async function handleSubmit() {
    loginError.value = false
    if (isSubmiting.value) {
        return
    }
    isSubmiting.value = true
    await auth.authenticate(username.value, password.value)
    await backend.init()
    if (!auth.user?.id) {
        loginError.value = true

        //todo: auto error
    }
    isSubmiting.value = false
}
</script>
