<template>
    <div class="container">
        <div class="container-table">
            <custom-table />
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
        },
    },
    async mounted() {
        await this.userStore.all();
    },
};
</script>

<style></style>
