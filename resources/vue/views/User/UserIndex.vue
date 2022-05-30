<template>
    <div class="container">
        <div class="container-table">
            <custom-table
                @row-selected="showLimitRequestTable"
                :columns="headers"
                :rows="users"
            />
        </div>
        <div v-if="showLimitTable" class="limit-request-table">
            <custom-table
                @row-selected="showLimitModal"
                :columns="userLimitHeaders"
                :rows="userLimits"
            />
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
import { mapStores } from "pinia";
import { useUserStore } from "../../stores/UserStore";
import userLimitColumns from "../../helpers/TableHeaders/UserLimit";

export default {
    components: {
        CustomTable: defineAsyncComponent(() =>
            import("../../components/Dashboard/Table.vue")
        ),
    },
    data() {
        return {
            showLimitTable: false,
            showLimitInformation: false,
            userSelected: {},
            limitInformationSelected: {},
        };
    },
    computed: {
        ...mapStores(useUserStore),
        users() {
            return this.userStore.list;
        },
        headers() {
            return this.userStore.tableHeaders;
        },
        userLimitHeaders() {
            return userLimitColumns;
        },
        userLimits() {
            return this.userSelected.limit;
        },
    },
    methods: {
        showLimitRequestTable(rowClicked) {
            this.userSelected = rowClicked;
            this.showLimitTable = true;
        },
        showLimitModal(limitClicked) {
            this.limitInformationSelected = limitClicked;
            this.showLimitInformation = true;
        }
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
