export const MAX_USERS_IN_TOOLTIP = 10

export interface MintReactionUser {
    id: string
    name: string
}

export interface MintReaction {
    type: string
    user: MintReactionUser
}

export const REACTION_TYPES = [
    {
        type: 'like',
        icon: '👍',
    },
    {
        type: 'love',
        icon: '❤️',
    },
    {
        type: 'party',
        icon: '🥳',
    },
    {
        type: 'laugh',
        icon: '😆',
    },
    {
        type: 'wow',
        icon: '😲',
    },
]
