require("./bootstrap");

import { createApp } from "vue";
import App from "../vue/App.vue";
import VueTableLite from "vue3-table-lite";
import { createPinia } from "pinia";
import Toast from "vue-toastification";
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

// toast-styles
import "vue-toastification/dist/index.css";

createApp(App)
    .use(createPinia())
    .use(Toast)
    .component("table-lite", VueTableLite)
    .component("data-table", Vue3EasyDataTable)
    .mount("#main");
