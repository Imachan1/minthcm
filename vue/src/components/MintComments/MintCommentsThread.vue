<template>
    <div class="mint-comments-thread">
        <MintCommentsMessage
            :comment="props.thread"
            @toggle-expand="replySectionExpanded = !replySectionExpanded"
            :pinned="props.pinned"
        />
        <v-expand-transition>
            <div v-if="replySectionExpanded" class="mint-comments-thred-replies-section">
                <div v-if="props.thread.replies.length" class="mint-comments-thread-replies">
                    <MintCommentsMessage
                        v-for="reply in props.thread.replies"
                        :key="reply.id"
                        :comment="reply"
                        :pinned="props.pinned"
                    />
                </div>
                <MintWysiwyg v-model="replyCommentDescription">
                    <template #footer>
                        <MintButton
                            class="ms-auto"
                            variant="primary"
                            :text="'Odpowiedz'"
                            :disabled="!replyCommentDescription"
                            icon="mdi-send"
                            @click="handleReplyClick"
                        />
                    </template>
                </MintWysiwyg>
            </div>
        </v-expand-transition>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { ThreadComment, useMintCommentsStore } from './MintCommentsStore'
import MintCommentsMessage from './MintCommentsMessage.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintWysiwyg from '@/components/MintWysiwyg.vue'

interface Props {
    thread: ThreadComment
    pinned?: boolean
}

const props = defineProps<Props>()
const store = useMintCommentsStore()

const replyCommentDescription = ref('')
const replySectionExpanded = ref(false)

async function handleReplyClick() {
    if (replyCommentDescription.value) {
        await store.addComment(replyCommentDescription.value, props.thread.id)
        replyCommentDescription.value = ''
        store.fetchComments()
    }
}
</script>

<style scoped lang="scss">
.mint-comments-thread {
    .mint-comments-thred-replies-section {
        padding: 8px 0px 0px 82px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        .mint-comments-thread-replies {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    }
}
</style>
