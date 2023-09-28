<template>
    <div class="mint-comments-users-hint">
        <div
            v-for="user in store.users"
            :key="user.id"
            class="mint-comments-users-hint-user"
            v-ripple
            @click="emit('user-click', user.user_name)"
        >
            <div class="mint-comments-users-hint-avatar">
                <img v-if="user.photo" :src="`legacy/index.php?entryPoint=download&type=Users&id=${user.id}_photo`" />
                <v-icon v-else icon="mdi-account" />
            </div>
            <div>{{ user.name }}</div>
        </div>
        <div v-if="!store.users?.length" class="mint-comments-users-hint-message" v-text="languages.label('LBL_MINT4_COMMENTS_USERS_HINT_NOT_FOUND')" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useMintCommentsStore } from './MintCommentsStore'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    query: string
}

const props = defineProps<Props>()
const emit = defineEmits(['user-click'])

const store = useMintCommentsStore()
const languages = useLanguagesStore()

const usersList = computed(() => {
    return false
})
</script>

<style scoped lang="scss">
.mint-comments-users-hint {
    padding: 8px 0px;
    background: white;
    border: thin solid #0003;
    min-width: 270px;
    height: 100%;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;

    .mint-comments-users-hint-message {
        padding: 16px;
    }

    .mint-comments-users-hint-user {
        display: flex;
        gap: 16px;
        align-items: center;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.43px;
        color: rgb(var(--v-theme-secondary));

        &:hover {
            background: #0001;
            cursor: pointer;
        }
    }

    .mint-comments-users-hint-avatar {
        display: flex;
        height: fit-content;
        > * {
            font-size: 20px;
            background: #eee;
            color: #444;
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
        }
    }
}
</style>
