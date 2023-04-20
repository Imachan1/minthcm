import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

import { createVuetify } from 'vuetify'

export default createVuetify({
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
