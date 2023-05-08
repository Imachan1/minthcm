<template>
    <div class="modules-popup">
        <v-tooltip
            v-for="mod in backend.modules"
            :key="mod.label"
            :text="mod.name"
            z-index="20000"
            location="top center"
        >
            <template v-slot:activator="{ props }">
                <button
                    class="module-tile"
                    v-bind="props"
                    v-ripple
                    @click="handleTileClick(mod)"
                >
                    <v-icon :icon="`mdi-${mod.icon}`" />
                    <span v-text="mod.name" />
                </button>
            </template>
        </v-tooltip>
    </div>
</template>

<script setup lang="ts">
import { defineEmits } from 'vue'
import { useRouter } from 'vue-router'
import { useBackendStore, Module } from '@/store/backend'

const router = useRouter()
const backend = useBackendStore()
const emit = defineEmits(['close'])

function handleTileClick(mod: Module) {
    router.push(
        ![
            'Calls',
            'Candidates',
            'Meetings',
            'Tasks',
            'Candidatures',
            'Positions',
            'Recruitments',
        ].includes(mod.label)
            ? `/${mod.label}`
            : `/${mod.label}/ESListView`,
    )
    emit('close')
}
</script>

<style scoped lang="scss">
.modules-popup {
    display: flex;
    flex-wrap: wrap;
    width: 80vw;
    max-width: 1300px;
    gap: 16px;
    padding: 16px 24px;
    .module-tile {
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: all 150ms ease-in-out;
        border-radius: 8px;
        width: 96px;
        height: 96px;
        padding: 8px 8px;
        align-items: center;
        color: rgb(var(--v-theme-secondary));
        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
            background: #e0ece9;
        }

        span {
            font-size: 14px;
            letter-spacing: 0.13px;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 88px;
        }
    }
}
</style>
