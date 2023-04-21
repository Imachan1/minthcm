<template>
    <div class="list-header">
        <v-btn
            class="ms-auto"
            color="secondary"
            prepend-icon="mdi-playlist-plus"
            @click="asdfg"
        >
            <span v-text="'LBL_ESLIST_COLUMNS'" />
        </v-btn>
    </div>
</template>

<script setup lang="ts">
import { useListViewStore } from './ListViewStore'

const store = useListViewStore()
function asdfg() {
    console.log('asdfg')
}
</script>

<style scoped lang="scss">
.list-header {
    width: 100%;
    background: rgb(var(--v-theme-surface));
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
}
</style>
