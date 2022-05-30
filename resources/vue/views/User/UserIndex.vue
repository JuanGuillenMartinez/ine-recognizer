<template>
    <div class="container">
        <div class="container-table">
            <custom-table :columns="headers" :rows="users" />
        </div>
        <div class="container-user-information"></div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
import { mapStores } from "pinia";
import { useUserStore } from "../../stores/UserStore";

export default {
    components: {
        CustomTable: defineAsyncComponent(() =>
            import("../../components/Dashboard/Table.vue")
        ),
    },
    computed: {
        ...mapStores(useUserStore),
        users() {
            return this.userStore.list;
        },
        headers() {
            return this.userStore.tableHeaders;
        },
    },
    async mounted() {
        await this.userStore.all();
    },
};
</script>

<style scoped>
.container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    max-width: none;
}
</style>
