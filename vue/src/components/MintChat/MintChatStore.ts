import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import MintChatDefault from './MintChatDefault.vue'
import MintChatEdit from './MintChatEdit.vue'
import MintChatList from './MintChatList.vue'
import MintChatDetail from './MintChatDetail.vue'

interface ChatUser {
    id: string
    name: string
    first_name?: string
    last_name: string
    photo?: string //url
    date_read?: string
}

export interface ChatMessage {
    id: string
    text: string
    date_entered: string
    user_id: string
}

interface Conversation {
    id: string
    name: string
    type: 'private' | 'group'
    messages?: ChatMessage[] // last message
    date_active: string // db format datetime
    date_read?: string // current user date read
    users: ChatUser[]
}

export const useMintChatStore = (key = 'mint') =>
    defineStore(`chat-${key}`, () => {
        // Views
        const views = {
            default: MintChatDefault,
            edit: MintChatEdit,
            list: MintChatList,
            detail: MintChatDetail,
        }
        const view = ref<'default' | 'edit' | 'list' | 'detail'>('default')
        const currentView = computed(() => views[view.value] || views['default'])
        function openDetailView(conversationId: string) {
            activeConversationId.value = conversationId
            view.value = 'detail'
        }

        // Chat Users
        const chatUsersLoading = ref(false)
        const chatUsersSearchQuery = ref('')
        const chatUsers = ref<ChatUser[]>([
            {
                id: '1',
                name: 'Michał T',
                first_name: 'Michał',
                last_name: 'T',
                photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=1_photo',
            },
            {
                id: 'aa572a00-60de-f9e1-f5b2-643800e9ead5',
                name: 'John Smith',
                first_name: 'John',
                last_name: 'Smith',
                photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=aa572a00-60de-f9e1-f5b2-643800e9ead5_photo',
            },
            {
                id: '2e220d12-7168-ad9f-e546-646dae62397c',
                name: 'Amanda Adams',
                first_name: 'Amanda',
                last_name: 'Adams',
                photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=2e220d12-7168-ad9f-e546-646dae62397c_photo',
            },
            {
                id: 'b14ccdae-7d0a-8fda-6853-646dbb76c04a',
                name: 'Eva Hoffman',
                first_name: 'Eva',
                last_name: 'Hoffman',
                photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=b14ccdae-7d0a-8fda-6853-646dbb76c04a_photo',
            },
        ])
        const usersList = computed(() => {
            const usersList = chatUsers.value.filter(
                (u) => !chatUsersSearchQuery.value || u.name.includes(chatUsersSearchQuery.value),
            )
            return usersList.sort((a, b) => a.name.localeCompare(b.name, 'pl'))
        })

        // Conversations
        const activeConversationId = ref<string | null>(null)
        const activeConversation = computed(() =>
            conversationsList.value.find((c) => c.id === activeConversationId.value),
        )
        const conversationsSearchQuery = ref('')
        const conversations = ref<Conversation[]>([
            {
                id: 'abcd',
                name: 'Michał T',
                type: 'private',
                messages: [{ id: 'm1', text: 'testowy message', date_entered: '2023-05-23 10:00:00', user_id: '1' }],
                date_active: '2023-05-22 08:00:00',
                date_read: '2023-05-22 07:50:00',
                users: [
                    {
                        id: '1',
                        name: 'Michał T',
                        first_name: 'Michał',
                        last_name: 'T',
                        photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=1_photo',
                    },
                ],
            },
            {
                id: 'aaaa',
                name: 'John Smith',
                type: 'private',
                messages: [
                    {
                        id: 'm1',
                        text: 'Perfect. Once you’re done, let’s have a quick meeting to go over it together.',
                        date_entered: '2023-05-22 10:00:00',
                        user_id: '1',
                    },
                    { id: 'm2', text: 'test', date_entered: '2023-05-23 10:00:20', user_id: '2' },
                    { id: 'm22', text: 'asfasfas asfasf', date_entered: '2023-05-23 10:00:23', user_id: '2' },
                    { id: 'm3', text: '😀', date_entered: '2023-05-23 10:01:20', user_id: '1' },
                    { id: 'm33', text: 'asfasfas fasfsa', date_entered: '2023-05-24 10:51:26', user_id: '1' },
                    { id: 'm34', text: 'Thanks!', date_entered: '2023-05-24 10:54:05', user_id: '1' },
                    { id: 'm35', text: 'Looking forward to reviewing the final report.', date_entered: '2023-05-24 10:54:26', user_id: '1' },
                    { id: 'm36', text: '👀', date_entered: '2023-05-24 10:54:32', user_id: '1' },
                ],
                date_active: '2023-04-21 09:00:00',
                date_read: '2023-05-22 07:50:00',
                users: [
                    {
                        id: 'aa572a00-60de-f9e1-f5b2-643800e9ead5',
                        name: 'John Smith',
                        first_name: 'John',
                        last_name: 'Smith',
                        photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=aa572a00-60de-f9e1-f5b2-643800e9ead5_photo',
                    },
                ],
            },
            {
                id: 'qwerty',
                name: 'Szkolenie BHP',
                type: 'group',
                messages: [{ id: 'm1', text: 'testowy message', date_entered: '2023-05-23 10:00:00', user_id: '1' }],
                date_active: '2023-05-20 08:00:00',
                users: [
                    {
                        id: '1',
                        name: 'Michał T',
                        first_name: 'Michał',
                        last_name: 'T',
                        photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=1_photo',
                    },
                    {
                        id: 'aa572a00-60de-f9e1-f5b2-643800e9ead5',
                        name: 'John Smith',
                        first_name: 'John',
                        last_name: 'Smith',
                        photo: 'aa572a00-60de-f9e1-f5b2-643800e9ead5_photo',
                    },
                ],
            },
            {
                id: 'vvvv',
                name: 'Amanda Adams',
                type: 'private',
                messages: [
                    {
                        id: 'm1',
                        text: 'testowy message',
                        date_entered: '2023-05-23 10:00:00',
                        user_id: '2e220d12-7168-ad9f-e546-646dae62397c',
                    },
                ],
                date_active: '2023-05-20 08:00:00',
                users: [
                    {
                        id: '2e220d12-7168-ad9f-e546-646dae62397c',
                        name: 'Amanda Adams',
                        first_name: 'Amanda',
                        last_name: 'Adams',
                        photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=2e220d12-7168-ad9f-e546-646dae62397c_photo',
                    },
                ],
            },
            {
                id: 'bbbb',
                name: 'Eva Hoffman',
                type: 'private',
                messages: [
                    {
                        id: 'm1',
                        text: 'Hi 😊',
                        date_entered: '2023-05-23 12:00:00',
                        user_id: 'b14ccdae-7d0a-8fda-6853-646dbb76c04a',
                    },
                ],
                date_active: '2023-05-23 12:00:00',
                users: [
                    {
                        id: 'b14ccdae-7d0a-8fda-6853-646dbb76c04a',
                        name: 'Eva Hoffman',
                        first_name: 'Eva',
                        last_name: 'Hoffman',
                        photo: '/minthcm/legacy/index.php?entryPoint=download&type=Users&id=b14ccdae-7d0a-8fda-6853-646dbb76c04a_photo',
                    },
                ],
            },
        ])
        const unreadConversationsCount = computed(
            () => conversations.value.filter((c) => !c.date_read || c.date_read < c.date_active).length || 0,
        )
        const conversationsList = computed(() => {
            const conversationsList = conversations.value.filter(
                (c) => !conversationsSearchQuery.value || c.name.includes(conversationsSearchQuery.value),
            )
            return conversationsList.sort((a, b) => (a.date_active > b.date_active ? -1 : 1))
        })

        return {
            view,
            currentView,
            openDetailView,
            chatUsersSearchQuery,
            usersList,
            chatUsersLoading,
            activeConversationId,
            activeConversation,
            conversationsSearchQuery,
            conversations,
            conversationsList,
            unreadConversationsCount,
        }
    })()
