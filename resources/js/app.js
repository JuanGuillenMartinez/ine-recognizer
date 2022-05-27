require("./bootstrap");

import { createApp } from "vue";
import App from "../vue/App.vue";
import VueTableLite from "vue3-table-lite";
import { createPinia } from 'pinia'

createApp(App)
    .use(createPinia())
    .component("table-lite", VueTableLite)
    .mount("#main");
