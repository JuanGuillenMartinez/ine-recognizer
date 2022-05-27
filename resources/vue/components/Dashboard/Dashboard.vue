<template>
    <div class="container-dashboard">
        <div class="container-users"><user-index /></div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
import UserIndex from "../../views/User/UserIndex.vue";
export default {
    components: {
        UserIndex,
    },
};
</script>

<style scoped>
.container-dashboard {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
</style>
