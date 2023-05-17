<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_FORGET_TITLE')" />
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
            v-model="email"
            color="primary"
            base-color="#00000099"
            density="comfortable"
            :label="languages.label('LBL_MINT4_AUTH_EMAIL')"
            variant="outlined"
            hide-details
            :disabled="isSubmiting"
        />
        <MintButton variant="primary" :text="languages.label('LBL_MINT4_AUTH_FORGET_BTN')" @click="null" />
    </div>
</template>

<script setup lang="ts">
import { ref, inject } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import MintButton from '@/components/MintButton.vue'

const backend = useBackendStore()
const languages = useLanguagesStore()
const auth = useAuthStore()

const username = ref('')
const email = ref('')
const isSubmiting = ref(false)
</script>
