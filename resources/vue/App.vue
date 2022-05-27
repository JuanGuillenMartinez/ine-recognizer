<template>
    <dashboard />
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        Dashboard: defineAsyncComponent(() =>
            import("./components/Dashboard/Dashboard.vue")
        ),
    },
};
</script>

<style scoped></style>
