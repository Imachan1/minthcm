<template>
    <div
        class="setup-wizard-cookies-container"
        v-html="languages.label('LBL_MINT4_SETUP_WIZARD_COOKIES_DESCRIPTION')"
    />
</template>

<script setup lang="ts">
import { useSetupWizardStore } from '../SetupWizardStore'
import { useLanguagesStore } from '@/store/languages'

const store = useSetupWizardStore()
const languages = useLanguagesStore()
</script>

<style scoped lang="scss">
.setup-wizard-cookies-container {
    font-size: 16px;
    letter-spacing: 0.5px;

    :deep(a) {
        color: rgb(var(--v-theme-secondary));
    }
}
</style>
