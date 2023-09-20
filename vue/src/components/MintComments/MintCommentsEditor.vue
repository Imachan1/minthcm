<template>
    <div class="comment-editor">
        <textarea :id="props.id || 'default'"></textarea>
        <div class="comment-editor-footer">
            <MintButton
                variant="primary"
                :text="props.isReply ? 'Odpowiedz' : 'Dodaj komentarz'"
                icon="mdi-send"
                @click="handleAddCommentClick"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import tinymce from 'tinymce'
import MintButton from '../MintButtons/MintButton.vue'
import { useMintCommentsStore } from './MintCommentsStore'

// import 'tinymce/icons/default'
// import 'tinymce/themes/silver'
// import 'tinymce/models/dom'
// import 'tinymce/skins/ui/tinymce-5/skin.css'

interface Props {
    id?: string
    isReply?: boolean
}

const props = defineProps<Props>()

const store = useMintCommentsStore()

const instance = ref(null)

onMounted(async () => {
    instance.value = await tinymce.init({
        selector: `.comment-editor textarea#${props.id || 'default'}`,
        promotion: false,
        menubar: false,
        statusbar: false,
        height: 240,
    })
})

async function handleAddCommentClick() {
    const description = tinymce.get(props.id || 'default')?.getContent()
    if (description) {
        await store.addComment(description)
        tinymce.get(props.id || 'default')?.setContent('')
        store.fetchComments()
    }
}
</script>

<style lang="scss">
.comment-editor {
    border: thin solid #0003;
    border-radius: 4px;

    .tox.tox-tinymce {
        border: none;
    }
}

.comment-editor-footer {
    display: flex;
    justify-content: flex-end;
    padding: 16px;
}
</style>
