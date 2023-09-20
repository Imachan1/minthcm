<template>
    <div
        :class="{
            'mint-comments-message-container': true,
            'mint-comments-message-reply': isReply,
            'mint-comments-message-owner': isOwner,
        }"
    >
        <div class="mint-comments-message-avatar">
            <img
                v-if="comment.assigned_user.photo"
                :src="`legacy/index.php?entryPoint=download&type=Users&id=${comment.assigned_user.id}_photo`"
            />
            <v-icon v-else icon="mdi-account" />
        </div>
        <div class="mint-comments-message" @mouseover="isHovering = true" @mouseleave="isHovering = false">
            <div class="mint-comments-message-header">
                <div class="mint-comments-message-header-info">
                    <span v-text="comment.assigned_user.name" />
                    <span class="mint-comments-message-edited" v-if="comment.edited" v-text="'(komentarz edytowany)'" />
                </div>
                <span class="mint-comments-message-header-date">{{ dateCreated }}</span>
            </div>
            <MintWysiwyg v-if="isEditMode" v-model="commentContent">
                <template #footer>
                    <div class="d-flex justify-space-between">
                        <MintButton variant="text" :text="'Anuluj'" @click="handleCancelEditClick" />
                        <MintButton
                            variant="primary"
                            :text="'Zapisz'"
                            :disabled="isSaveButtonDisabled"
                            icon="mdi-check"
                            @click="handleSaveClick"
                        />
                    </div>
                </template>
            </MintWysiwyg>
            <div
                v-else-if="props.comment.removed"
                class="mint-comments-message-deleted"
                v-text="'Komentarz usunięty'"
            />
            <div v-else class="mint-comments-message-content" v-html="comment.description" />
            <div v-if="!isEditMode && !props.comment.removed" class="mint-comments-message-footer">
                <div class="mint-comments-message-reactions">
                    <MintReactions v-if="comment.reactions?.length" :reactions="comment.reactions" />
                    <v-menu location="top" offset="8">
                        <template v-slot:activator="{ props, isActive }">
                            <v-fade-transition>
                                <MintButton
                                    v-if="isHovering || isActive"
                                    size="small"
                                    v-bind="props"
                                    icon="mdi-heart-plus"
                                    variant="nav"
                                    :active="isActive"
                                />
                            </v-fade-transition>
                        </template>
                        <MintReactionsActions
                            :active-reaction-type="currentUserReactionType"
                            @react="handleReactAction"
                            @delete-reaction="store.deleteCommentReaction(props.comment.id)"
                        />
                    </v-menu>
                </div>
                <div class="d-flex">
                    <v-fade-transition>
                        <MintButton
                            v-if="!isReply && isHovering"
                            variant="nav"
                            icon="mdi-reply"
                            size="small"
                            @click="emit('toggle-expand')"
                        />
                    </v-fade-transition>
                    <v-menu>
                        <template v-slot:activator="{ props, isActive }">
                            <v-fade-transition>
                                <MintButton
                                    v-if="isHovering || isActive"
                                    v-bind="props"
                                    icon="mdi-dots-vertical"
                                    variant="nav"
                                    size="small"
                                    :active="isActive"
                                />
                            </v-fade-transition>
                        </template>
                        <MintMenuList :items="commentMenuActions" />
                    </v-menu>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import he from 'he'
import { MintComment } from './MintCommentsStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import MintReactions from '@/components/MintReactions/MintReactions.vue'
import MintReactionsActions from '@/components/MintReactions/MintReactionsActions.vue'
import { DateTime } from 'luxon'
import { useMintCommentsStore } from './MintCommentsStore'
import { useAuthStore } from '@/store/auth'
import MintWysiwyg from '../MintWysiwyg.vue'

interface Props {
    comment: MintComment
    pinned?: boolean
}

const props = defineProps<Props>()
const emit = defineEmits(['toggle-expand'])

const store = useMintCommentsStore()
const auth = useAuthStore()

const isHovering = ref(false)
const isEditMode = ref(false)
const commentContent = ref(props.comment.description)

const isReply = computed(() => props.comment.reply_to_id)
const isOwner = computed(() => props.comment.assigned_user.id === auth.user?.id)

const isSaveButtonDisabled = computed(() => {
    return !commentContent.value || commentContent.value === props.comment.description
})

const dateCreated = computed(() => {
    const dt = DateTime.fromSQL(props.comment.date_entered, { zone: 'UTC' })
    return dt.toLocal().toFormat('dd.MM.yyyy HH:mm')
})

const currentUserReactionType = computed(() => {
    return props.comment.reactions?.find((reaction) => reaction.user.id === auth.user?.id)?.type
})

function handleReactAction(type: string) {
    if (type) {
        store.reactToComment(props.comment.id, type)
    }
}

const commentMenuActions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []
    if (auth.user?.id === props.comment.assigned_user.id && !props.comment.removed) {
        actions.push({
            title: 'Edytuj',
            icon: 'pencil',
            onClick: () => {
                isEditMode.value = true
            },
        })
        actions.push({
            title: 'Usuń',
            icon: 'delete',
            onClick: () => {
                store.deleteComment(props.comment.id)
            },
        })
    }
    actions.push({
        title: 'Cytuj',
        icon: 'format-quote-close',
        onClick: () => {
            return
        },
    })
    if (props.comment.pinned && props.pinned) {
        actions.push({
            title: 'Odepnij',
            icon: 'pin-off',
            onClick: () => store.unpinComment(props.comment.id),
        })
    } else if (!props.comment.pinned && !props.pinned) {
        actions.push({
            title: 'Przypnij',
            icon: 'pin',
            onClick: () => store.pinComment(props.comment.id),
        })
    }
    return actions
})

function handleCancelEditClick() {
    commentContent.value = props.comment.description
    isEditMode.value = false
}

async function handleSaveClick() {
    if (commentContent.value) {
        isEditMode.value = false
        await store.editCommentDescription(props.comment.id, commentContent.value)
        store.fetchComments()
    }
}
</script>

<style scoped lang="scss">
.mint-comments-message-container {
    display: flex;
    gap: 16px;
    width: 100%;
    margin-left: auto;
}

.mint-comments-message-avatar {
    display: flex;
    margin-top: 16px;

    > * {
        font-size: 32px;
        background: white;
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 50%;
        // width: 64px;
        // height: 64px;
        // border: 2px solid rgb(var(--v-theme-primary));
        // box-shadow: 1px 1px 6px #0003;
    }
}

.mint-comments-message {
    background: rgb(var(--v-theme-primary-lighter));
    width: 100%;
    min-height: 80px;
    border-radius: 16px;
    padding: 8px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    // border: 2px solid rgb(var(--v-theme-primary));
    // box-shadow: 1px 1px 8px #0002;

    .mint-comments-message-deleted {
        font-style: italic;
        color: #0008;
        letter-spacing: 0.43px;
        font-size: 0.9em;
    }

    .mint-comments-message-content {
        overflow-wrap: anywhere;
    }

    .mint-comments-message-header {
        font-weight: 600;
        font-size: 14px;
        color: rgb(var(--v-theme-primary));
        display: flex;
        justify-content: space-between;
        letter-spacing: 0.43px;

        .mint-comments-message-header-info {
            display: flex;
            align-items: center;
            gap: 8px;

            .mint-comments-message-edited {
                font-style: italic;
                color: #0008;
                letter-spacing: 0.43px;
                font-size: 11px;
                font-weight: 400;
            }

            .mint-comments-message-header-date {
                font-size: 12px;
                letter-spacing: 0.4px;
            }
        }
    }

    .mint-comments-message-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 26px;

        .mint-comments-message-reactions {
            display: flex;
            gap: 8px;
        }
    }
}
</style>
