<template>
    <button
        class="mint-button"
        :class="[
            `mint-button-${props.variant}`,
            isIcon && 'mint-button-icon',
            props.disabled && 'disabled',
            props.active && 'active',
        ]"
        v-ripple="!disabled"
        :icon="props.icon && !props.text ? props.icon : false"
    >
        <v-icon v-if="props.icon" :icon="props.icon" size="24" />
        <span v-if="props.text" v-text="props.text" />
    </button>
</template>

<script setup lang="ts">
import { defineProps, withDefaults, computed } from 'vue'

interface Props {
    icon?: string
    text?: string
    tooltip?: string
    variant?: 'text' | 'regular' | 'primary'
    size?: 'small' | 'medium' | 'large'
    disabled?: boolean
    active?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'regular',
    size: 'medium',
    disabled: false,
    active: false,
})

const isIcon = computed(() => props.icon && !props.text)
</script>

<style scoped lang="scss">
.mint-button {
    border-radius: 50px;
    font-weight: 600;
    transition: all 150ms ease-in-out;
    cursor: pointer;
    padding: 4px 12px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;

    &:focus-visible {
        outline: thin solid #0002;
    }
}

.mint-button-text {
    color: rgb(var(--v-theme-secondary));
    background: transparent;
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: #e0ece9;
    }
    &.disabled {
        cursor: default;
        color: #8b8b8b;
        background: transparent;
    }
}

.mint-button-regular {
    color: rgb(var(--v-theme-secondary-dark));
    background: #e0ece9;
    &:hover {
        background: #9ec4bc;
    }
    &.disabled {
        cursor: default;
        color: #8b8b8b;
        background: #e0e0e0;
    }
    &.active {
        color: white;
        background: rgb(var(--v-theme-secondary));
    }
}

.mint-button-primary {
    color: white;
    background: rgb(var(--v-theme-secondary));
    &:hover {
        background: rgb(var(--v-theme-secondary-dark));
    }
    &.disabled {
        cursor: default;
        color: #e0e0e0;
        background: #8b8b8b;
    }
}

.mint-button-icon {
    padding: 8px;
    border-radius: 50%;
}
</style>
