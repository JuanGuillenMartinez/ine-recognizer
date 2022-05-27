<template>
    <div class="container-dashboard">
        <div class="container-table">
            <div class="container-table">
                <custom-table />
            </div>
            <div class="container-user-information"></div>
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        CustomTable: defineAsyncComponent(() =>
            import("./components/Dashboard/Table.vue")
        ),
    },
};
</script>

<style scoped>
.container-dashboard {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
</style>
