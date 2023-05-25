import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import pinia from './store'
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import './main.scss'

loadFonts()

const app = createApp(App)

app.use(router).use(vuetify).use(pinia)

app.mount('#app')

//TODO: dev
import { DateTime } from 'luxon'
window.DateTime = DateTime