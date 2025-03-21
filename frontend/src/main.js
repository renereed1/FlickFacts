import './assets/main.css'

import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
import VueToastify from 'vue3-toastify'

import 'vue3-toastify/dist/index.css'

const app = createApp(App)

app.use(router)
app.use(VueToastify)

app.mount('#app')

console.log("Welcome to Flick Facts Franchise! 🎉");
