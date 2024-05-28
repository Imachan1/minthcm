<template>
    <div class="drawer">
        <div class="drawer-nav">
            <MintButton
                icon="mdi-thumb-up"
                variant="nav"
                :active="ux.drawer === 'kudos'"
                @click="ux.drawer = ux.drawer === 'kudos' ? null : 'kudos'"
            />
            <v-badge
                :content="chat.unreadConversationsCount"
                color="error"
                location="bottom end"
                :model-value="chat.unreadConversationsCount > 0"
                @click="ux.drawer = ux.drawer === 'chat' ? null : 'chat'"
            >
                <MintButton icon="mdi-chat" variant="nav" :active="ux.drawer === 'chat'" />
            </v-badge>
            <MintButton icon="mdi-newspaper-variant" variant="nav" />
        </div>
        <v-slide-x-transition>
            <div v-if="ux.drawer" class="drawer-content" ref="drawerContentRef" @scroll="handleScroll">
                <MintKudos v-if="ux.drawer === 'kudos'" />
                <MintChat v-if="ux.drawer === 'chat'" />
            </div>
        </v-slide-x-transition>
    </div>
</template>

<script setup lang="ts">
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintChat from '@/components/MintChat/MintChat.vue'
import MintKudos from '@/components/MintKudos/MintKudos.vue'
import { useMintChatStore } from '@/components/MintChat/MintChatStore'
import { useUxStore } from '@/store/ux'
import { ref } from 'vue'
import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'

const ux = useUxStore()
const chat = useMintChatStore()

const drawerContentRef = ref<any>(null)
const kudosStore = useMintKudosStore()

function handleScroll() {
    if (ux.drawer === 'kudos') {
        if (
            !kudosStore.fetchedAllKudos &&
            drawerContentRef?.value?.scrollTop + drawerContentRef?.value?.clientHeight >=
                drawerContentRef?.value.scrollHeight
        ) {
            kudosStore.fetchKudos()
        }
    }
}
</script>

<style scoped lang="scss">
.drawer {
    position: fixed;
    z-index: 1000;
    top: var(--v-top-nav-height);
    right: 0px;
    height: calc(100vh - var(--v-top-nav-height));
    box-shadow: 0px 1px 32px #0099761a;

    .drawer-content {
        width: var(--v-drawer-width);
        background: rgb(var(--v-theme-surface));
        height: 100%;
        overflow: auto;
    }
}
.drawer-nav {
    padding: 8px;
    display: flex;
    flex-direction: column;
    position: absolute;
    // left: calc(100vw - 400px - 70px);
    top: 50%;
    transform: translate(calc(-100% - 12px), -50%);
    gap: 4px;
    background: rgb(var(--v-theme-surface));
    border-radius: 100px;
    box-shadow: 0px 3px 6px #00000029;
}

.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
        margin-top: -8px;
        margin-left: -8px;
        font-weight: 600;
    }
}
</style>
