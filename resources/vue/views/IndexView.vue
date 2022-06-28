<template>
    <div class="tab-panel-container">
        <div class="user-container">
            <user-index v-if="userTabIsClicked" />
        </div>
        <div class="log-container">
            <log-index v-if="logTabIsClicked" />
        </div>
    </div>
    <div class="container-buttons">
        <float-button
            @button-clicked="showUserTab"
            color-class="btn-primary"
            title="Usuarios"
            :float="false"
            icon="fa-solid fa-people-group"
        />
        <float-button
            @button-clicked="showLogTab"
            color-class="btn-primary"
            title="Logs"
            :float="false"
            icon="fa-solid fa-file-lines"
        />
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        UserIndex: defineAsyncComponent(() => import("./User/UserIndex.vue")),
        LogIndex: defineAsyncComponent(() => import("./Log/LogIndex.vue")),
        FloatButton: defineAsyncComponent(() =>
            import("../components/Dashboard/FloatButton.vue")
        ),
    },
    data() {
        return {
            userTabIsClicked: true,
            logTabIsClicked: false,
        };
    },
    methods: {
        showUserTab() {
            this.userTabIsClicked = true;
            this.logTabIsClicked = false;
        },
        showLogTab() {
            this.userTabIsClicked = false;
            this.logTabIsClicked = true;
        },
    },
};
</script>

<style scoped>
.container-buttons {
    position: fixed;
    right: 12px;
    bottom: 12px;
    display: grid;
    grid-template-rows: 1fr 1fr;
    row-gap: 2px;
}
</style>
