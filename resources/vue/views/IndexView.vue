<template>
    <div class="tab-panel-container">
        <div class="user-container">
            <user-index v-if="userTabIsClicked" />
        </div>
        <div class="log-container">
            <log-index v-if="logTabIsClicked" />
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        UserIndex: defineAsyncComponent(() => import("./User/UserIndex.vue")),
        LogIndex: defineAsyncComponent(() => import("./Log/LogIndex.vue")),
    },
    data() {
        return {
            userTabIsClicked: true,
            logTabIsClicked: false,
        };
    },
};
</script>

<style scoped></style>
