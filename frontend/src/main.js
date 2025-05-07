import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

//Bootstrap: css, js
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
//Icons: css
import "bootstrap-icons/font/bootstrap-icons.min.css"

import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"

import Modal from './components/Modal.vue'

const app = createApp(App)

app.use(Toast, {
    // Globális opciók
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
})

app.component("Modal", Modal);

app.use(createPinia())
app.use(router)

app.mount('#app')