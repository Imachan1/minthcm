import { useMintChatStore } from '@/components/MintChat/MintChatStore'
import MintChat from '../components/MintChat/MintChat.vue'

export default {
    icon: 'mdi-chat',
    component: MintChat,
    badge: () => useMintChatStore().unreadConversationsCount,
}
