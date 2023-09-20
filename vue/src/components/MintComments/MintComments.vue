<template>
    <div class="mint-comments">
        <v-fade-transition>
            <div v-if="store.isLoading" class="mint-comments-overlay">
                <v-progress-circular size="100" width="7" color="primary" indeterminate />
            </div>
        </v-fade-transition>
        <h1 class="mb-4">Przypięte komentarze</h1>
        <div class="mint-comments-threads">
            <MintCommentsThread v-for="thread in store.pinnedThreads" :key="thread.id" :thread="thread" pinned />
        </div>
        <h1 class="mb-4">Komentarze</h1>
        <div class="mint-comments-threads">
            <MintCommentsThread v-for="thread in store.threads" :key="thread.id" :thread="thread" />
        </div>
        <MintWysiwyg v-model="newCommentDescription">
            <template #footer>
                <MintButton
                    class="ms-auto"
                    variant="primary"
                    :text="'Dodaj komentarz'"
                    :disabled="!newCommentDescription"
                    icon="mdi-send"
                    @click="handleAddCommentClick"
                />
            </template>
        </MintWysiwyg>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import MintCommentsThread from './MintCommentsThread.vue'
import MintWysiwyg from '@/components/MintWysiwyg.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useMintCommentsStore } from './MintCommentsStore'

const store = useMintCommentsStore()
const newCommentDescription = ref('')

onMounted(() => {
    store.fetchInitialData()
})

async function handleAddCommentClick() {
    if (newCommentDescription.value) {
        await store.addComment(newCommentDescription.value)
        newCommentDescription.value = ''
        store.fetchComments()
    }
}
</script>

<style scoped lang="scss">
.mint-comments {
    position: relative;
    padding: 32px;
    min-width: 720px;
    max-width: 720px;
    display: flex;
    flex-direction: column;
    gap: 48px;
}

.mint-comments-overlay {
    position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 1000;
    background: #0002;
    display: flex;
    justify-content: center;
    align-items: center;
}

.mint-comments-threads {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 32px;
}
</style>
