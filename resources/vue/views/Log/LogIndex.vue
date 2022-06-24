<template>
    <title-tab>Peticiones</title-tab>
    <div class="card">
        <div class="search">
            <div class="search-input">
                <label class="form-label">ID</label>
                <input v-model="idInput" type="number" class="form-control" />
            </div>
            <div class="search-input">
                <label class="form-label">Dirección IP</label>
                <input v-model="ipInput" type="text" class="form-control" />
            </div>
            <div class="search-input">
                <label class="form-label">Usuario</label>
                <input
                    v-model="usuarioInput"
                    type="text"
                    class="form-control"
                />
            </div>
        </div>
        <div class="card-body">
            <log-table
                @do-search="searchRows"
                :total-count="logStore.pagination.total"
                :columns="logHeaders"
                :rows="logs"
                :is-loading="tableIsLoading"
            />
            <!-- <custom-table :columns="logHeaders" :rows="logs" /> -->
        </div>
    </div>
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
        TitleTab: defineAsyncComponent(() =>
            import("../../components/Dashboard/TitleTab.vue")
        ),
        LogTable: defineAsyncComponent(() =>
            import("../../components/Log/LogTable.vue")
        ),
    },
    data() {
        return {
            logHeaders: logColumns,
            logs: [],
            tableIsLoading: true,
            ipInput: "",
            usuarioInput: "",
            idInput: "",
        };
    },
    computed: {
        ...mapStores(useLogStore),
    },
    watch: {
        ipInput(newVal) {
            this.logs = this.logStore.filter("ip", newVal);
        },
        usuarioInput(newVal) {
            this.logs = this.logStore.filter("user", newVal);
        },
        idInput(newVal) {
            this.logs = this.logStore.filter("id", newVal);
        },
    },
    methods: {
        async searchRows(params) {
            this.tableIsLoading = true;
            const response = await this.logStore.rowsPaginated(params.page, params.perPage);
            this.logs = response.data;
            this.tableIsLoading = false;
        },
    },
    async mounted() {
        const response = await this.logStore.all();
        this.logs = response.data;
        this.tableIsLoading = false;
    },
};
</script>

<style scoped>
.card {
    margin-top: 8px;
}
.search {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin-top: 12px;
    margin-right: 1rem;
    margin-left: 1rem;
}
.search-input {
    margin: 2px;
}
</style>
