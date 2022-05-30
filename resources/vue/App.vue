<template>
    <index-view />
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        IndexView: defineAsyncComponent(() => import("./views/IndexView.vue")),
    },
};
</script>

<style>
div#main {
    max-width: none;
}
</style>
