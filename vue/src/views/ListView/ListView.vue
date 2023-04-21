<template>
    <div class="pa-8">
        <h1 v-text="module" />
        <div class="elevation-4">
            <ListViewHeader />
            <ListViewTable />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, watch, onMounted } from 'vue'
import ListViewHeader from './ListViewHeader.vue'
import ListViewTable from './ListViewTable.vue'
import { useListViewStore } from './ListViewStore'
import { useUrlStore } from '@/store/url'

const url = useUrlStore()
const store = useListViewStore()
console.log('ListView init')

const module = computed(() => url.module)

onMounted(async () => {
    await store.init()
    await store.getData()
})

watch(module, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        console.log('lv watch')
        store.init()
    }
})

</script>

<style scoped lang="scss"></style>
