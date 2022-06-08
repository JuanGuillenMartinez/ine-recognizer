import { defineStore } from "pinia";
import { getAll, get, post, put } from "../helpers/Request";
import headers from "../helpers/TableHeaders/User";

const baseUrl = "/users";

export const useUserStore = defineStore("user", {
    state: () => {
        return {
            list: [],
            listFinded: [],
            isLoading: true,
            finded: {},
            tableHeaders: headers,
            selected: {},
            paginate: {},
            userCredentialsSelected: {},
        };
    },
    actions: {
        async all() {
            try {
                this.isLoading = true;
                const response = await getAll(baseUrl);
                const { data } = response;
                this.list = data;
                this.isLoading = false;
                return response;
            } catch (error) {
                const {
                    response: { data },
                } = error;
                return data;
            }
        },
        async find(id) {
            this.isLoading = true;
            const response = await get(baseUrl + "/" + id);
            const { data } = response;
            this.finded = data;
            this.isLoading = false;
            return response;
        },
        async add(object) {
            this.isLoading = true;
            const response = await post(baseUrl, object);
            await this.all();
            this.isLoading = false;
            return response;
        },
        async searchByFolder(id) {
            this.isLoading = true;
            const response = await get(`/folder-groups/${id}/folders`);
            this.listFinded = response.data;
            this.isLoading = false;
            return response;
        },
        async updateLimits(userId, limitId, limit) {
            this.isLoading = true;
            const response = await put(`${baseUrl}/${userId}/limits`, {
                user_limit_id: limitId,
                limit,
            });
            this.isLoading = false;
            return response;
        },
        async changePage(next) {},
        async findCredentials(userId) {
            const response = await get(`${baseUrl}/${userId}/credentials`);
            this.userCredentialsSelected = response.data;
        },
        async generateToken(userId) {
            const response = await post(`${baseUrl}/${userId}/token`);
            this.userCredentialsSelected.token = response.data.token;
            return response;
        },
        async registerCommerce(properties) {
            const response = await post("/commerces", properties);
            return response;
        },
        async banUser(userId) {
            console.log(userId);
            const response = await post(`${baseUrl}/${userId}/ban`, {});
            return response;
        },
    },
    getters: {
        filter: (state) => {
            return (attribute, searchParam) => {
                if (searchParam === "") {
                    return state.list;
                } else {
                    return state.list.filter((x) =>
                        x[attribute].toString().includes(searchParam)
                    );
                }
            };
        },
    },
});
