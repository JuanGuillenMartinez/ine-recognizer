<template>
    <title-tab>Usuarios</title-tab>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="container-search">
                    <div class="search-input">
                        <label class="form-label">Nombre</label>
                        <input
                            v-model="inputName"
                            type="text"
                            class="form-control input-search"
                        />
                    </div>
                    <div class="search-input">
                        <label class="form-label">Correo electrónico</label>
                        <input
                            v-model="inputEmail"
                            type="text"
                            class="form-control input-search"
                        />
                    </div>
                </div>
                <custom-table
                    class="table-users"
                    @row-selected="showLimitRequestTable"
                    :columns="headers"
                    :rows="users"
                />
            </div>
            <div v-if="showLimitTable" class="limit-request-table">
                <div class="search-input">
                    <label class="form-label">Tipo de petición</label>
                    <input
                        v-model="requestInput"
                        type="text"
                        class="form-control input-search"
                    />
                </div>
                <custom-table
                    class="limit-table"
                    @row-selected="showLimitModal"
                    :columns="userLimitHeaders"
                    :rows="userLimits"
                />
            </div>
            <custom-modal
                @close-modal="closeLimitInformation"
                :visible="showLimitInformation"
                title="Actualizar limite de peticiones"
                title-close="Cerrar"
                ><limit-form
                    @save-clicked="updateLimit"
                    :object="limitInformationSelected"
            /></custom-modal>
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
        CustomModal: defineAsyncComponent(() =>
            import("../../components/Dashboard/CustomModal.vue")
        ),
        LimitForm: defineAsyncComponent(() =>
            import("../../components/Limit/LimitForm.vue")
        ),
        TitleTab: defineAsyncComponent(() =>
            import("../../components/Dashboard/TitleTab.vue")
        ),
    },
    data() {
        return {
            showLimitTable: false,
            showLimitInformation: false,
            userSelected: {},
            limitInformationSelected: {},
            inputName: "",
            inputEmail: "",
            requestInput: "",
            users: [],
            userLimits: [],
        };
    },
    computed: {
        ...mapStores(useUserStore),
        headers() {
            return this.userStore.tableHeaders;
        },
        userLimitHeaders() {
            return userLimitColumns;
        },
    },
    methods: {
        showLimitRequestTable(rowClicked) {
            this.userSelected = rowClicked;
            this.showLimitTable = true;
            this.userLimits = rowClicked.limit;
        },
        showLimitModal(limitClicked) {
            this.limitInformationSelected = limitClicked;
            this.showLimitInformation = true;
        },
        closeLimitInformation() {
            this.showLimitInformation = false;
        },
        async updateLimit(limitInformation) {
            const response = await this.userStore.updateLimits(
                this.userSelected.id,
                this.limitInformationSelected.id,
                limitInformation.limit
            );
            await this.userStore.all();
            this.userSelected = this.userStore.list.reduce((user) => {
                if (user.id === this.userSelected.id) {
                    return user;
                }
            });
            this.userLimits = this.userSelected.limit;
            this.closeLimitInformation();
        },
    },
    watch: {
        inputName(newValue) {
            this.users = this.userStore.filter("name", newValue);
        },
        inputEmail(newValue) {
            this.users = this.userStore.filter("email", newValue);
        },
        requestInput(newValue) {
            this.userLimits = this.userSelected.limit.filter((limitRequest) => {
                return limitRequest.request.toString().includes(newValue);
            });
        },
    },
    async mounted() {
        await this.userStore.all();
        this.users = this.userStore.list;
    },
};
</script>

<style scoped>
.card-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    max-width: none;
}
.card {
    margin-top: 8px;
}
.container-search {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 4px;
}
.table-users,
.limit-table {
    margin-top: 8px;
}
</style>
