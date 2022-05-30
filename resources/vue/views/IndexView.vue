<template>
    <div class="tab-panel-container">
        <div class="user-container">
            <user-index v-if="userTabIsClicked" />
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        UserIndex: defineAsyncComponent(() => import("./User/UserIndex.vue")),
    },
    data() {
        return {
            userTabIsClicked: true,
        };
    },
};
</script>

<style scoped></style>
