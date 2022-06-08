<template>
    <title-tab>
        <button
            style="float: left"
            @click="openUserForm"
            type="button"
            class="btn btn-warning btn-add-user"
        >
            <i
                style="margin-right: 0px; font-size: 20px; padding: 0.25rem"
                class="fa-solid fa-add"
            ></i>
        </button>
        <h1>Usuarios</h1>
    </title-tab>
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
                    :columns="headers"
                    :rows="users"
                    @row-selected="showLimitRequestTable"
                    @do-search="changePage"
                    @is-finished="tableLoadingFinish"
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
            <custom-modal
                @close-modal="showCredentialsInformation = false"
                :visible="showCredentialsInformation"
                title="Credenciales del usuario"
                title-close="Cerrar"
            >
                <credentials-form
                    @generate-token="generateToken"
                    :readonly="true"
                    :object="userStore.userCredentialsSelected"
                />
            </custom-modal>
            <custom-modal
                @close-modal="showUserForm = false"
                :visible="showUserForm"
                title="Registrar usuario"
                title-close="Cerrar"
            >
                <user-form
                    @save-clicked="saveUser"
                    @invalid-email="
                        showAlert('El correo electrónico es invalido.')
                    "
                    @empty-inputs="
                        showAlert('Algunos campos no se encuentran rellenados.')
                    "
                />
            </custom-modal>
            <modal-custom
                @close-modal="showDisableModal = false"
                :visible="showDisableModal"
            >
                <template v-slot:title>
                    Bloquear usuario {{ `"${userSelected.name}"` }}
                </template>
                <template v-slot:body
                    >Todas las peticiones realizadas por el usuario serán
                    bloqueadas.</template
                >
                <template v-slot:footer>
                    <CButton color="light" @click="banUser">
                        Si, continuar
                    </CButton>
                    <CButton color="dark" @click="showDisableModal = false">
                        No
                    </CButton>
                </template>
            </modal-custom>
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from "@vue/runtime-core";
import { mapStores } from "pinia";
import { useUserStore } from "../../stores/UserStore";
import userLimitColumns from "../../helpers/TableHeaders/UserLimit";
import UserForm from "../../components/User/UserForm.vue";
import { useToast } from "vue-toastification";
import { CButton } from "@coreui/vue";

const toast = useToast();

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
        CredentialsForm: defineAsyncComponent(() =>
            import("../../components/User/CredentialsForm.vue")
        ),
        ModalCustom: defineAsyncComponent(() =>
            import("../../components/Dashboard/ModalCustom.vue")
        ),
        UserForm,
        CButton,
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
            showCredentialsInformation: false,
            showUserForm: false,
            showDisableModal: false,
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
            if (response.success) {
                toast.success(response.message);
            } else {
                toast.warning(response.message);
            }
            await this.userStore.all();
            this.userSelected = this.userStore.list.reduce((user) => {
                if (user.id === this.userSelected.id) {
                    return user;
                }
            });
            this.userLimits = this.userSelected.limit;
            this.closeLimitInformation();
        },
        async changePage(offset) {
            console.log(offset);
        },
        tableLoadingFinish(elements) {
            for (let item of elements) {
                this.addEventButtons(item);
            }
        },
        async addEventButtons(element) {
            if (element.classList.contains("btn-credentials")) {
                await element.addEventListener("click", async () => {
                    const userId = element.dataset.id;
                    await this.userStore.findCredentials(userId);
                    this.showCredentialsInformation = true;
                });
            }
            if (element.classList.contains("btn-disable")) {
                await element.addEventListener("click", async () => {
                    const userId = element.dataset.id;
                    this.showDisableModal = true;
                });
            }
        },
        async generateToken(properties) {
            const userId = properties.id;
            const response = await this.userStore.generateToken(userId);
            if (response.success) {
                toast.success(response.message);
            } else {
                toast.warning(response.message);
            }
        },
        openUserForm() {
            this.showUserForm = true;
        },
        async saveUser(properties) {
            const response = await this.userStore.registerCommerce(properties);
            this.showUserForm = false;
            if (response.success) {
                toast.success(response.message);
            } else {
                toast.warning(response.message);
            }
        },
        showAlert(message) {
            console.log(message);
        },
        async banUser() {
            const userId = this.userSelected.id;
            const response = await this.userStore.banUser(userId);
            this.showDisableModal = false;
            toast.success(response.message);
        },
        async unbanUser() {
            const userId = this.userSelected.id;
            const response = await this.userStore.banUser(userId);
            this.showDisableModal = false;
            toast.success(response.message);
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
        const response = await this.userStore.all();
        if (!response.success) {
            toast.error(response.message);
        }
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
.btn-add-user {
    color: #fff;
    background-color: #3890cf;
    border-color: rgb(0 0 0 / 25%);
}
</style>
