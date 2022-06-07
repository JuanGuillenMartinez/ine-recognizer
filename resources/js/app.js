require("./bootstrap");

import { createApp } from "vue";
import App from "../vue/App.vue";
import VueTableLite from "vue3-table-lite";
import { createPinia } from "pinia";
import Toast from "vue-toastification";

// toast-styles
import "vue-toastification/dist/index.css";

createApp(App)
    .use(createPinia())
    .use(Toast)
    .component("table-lite", VueTableLite)
    .mount("#main");
