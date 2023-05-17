<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_RESET_TITLE')" />
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
        <v-text-field
            v-model="password2"
            :type="showPassword ? 'text' : 'password'"
            color="primary"
            base-color="#00000099"
            density="comfortable"
            :label="languages.label('LBL_MINT4_AUTH_PASSWORD_REPEAT')"
            variant="outlined"
            hide-details
            :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showPassword = !showPassword"
            :disabled="isSubmiting"
        />
        <MintButton variant="primary" :text="languages.label('LBL_MINT4_AUTH_RESET_BTN')" @click="null" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import MintButton from '@/components/MintButton.vue'

const backend = useBackendStore()
const languages = useLanguagesStore()
const auth = useAuthStore()
const password = ref('')
const password2 = ref('')
const showPassword = ref(false)

const username = ref('')
const email = ref('')
const isSubmiting = ref(false)
</script>
