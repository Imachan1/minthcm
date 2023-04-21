import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

import { createVuetify } from 'vuetify'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

export default createVuetify({
    icons: {
        defaultSet: 'mdi',
        sets: { mdi },
        aliases: {
            ...aliases,
            sortAsc: 'mdi-chevron-up',
            sortDesc: 'mdi-chevron-down',
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#00654e',
                    'primary-light': '#e0ecea',
                    secondary: '#145d7b',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#00654e',
                    'primary-light': '#e0ecea',
                    secondary: '#145d7b',
                },
            },
        },
    },
})
