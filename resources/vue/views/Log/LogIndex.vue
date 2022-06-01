<template>
    <custom-table :columns="logHeaders" :rows="logs" />
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
import logColumns from "../../helpers/TableHeaders/Log";
import { mapStores } from "pinia";
import { useLogStore } from "../../stores/LogStore";
export default {
    components: {
        CustomTable: defineAsyncComponent(() =>
            import("../../components/Dashboard/Table.vue")
        ),
    },
    data() {
        return {
            logHeaders: logColumns,
        };
    },
    computed: {
        ...mapStores(useLogStore),
        logs() {
            return this.logStore.list;
        },
    },
    async mounted() {
        await this.logStore.all();
    },
};
</script>

<style></style>
