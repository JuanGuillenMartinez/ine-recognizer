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
        };
    },
    actions: {
        async all() {
            this.isLoading = true;
            const response = await getAll(baseUrl);
            const { data } = response;
            this.list = data;
            this.paginate = response.links;
            this.isLoading = false;
            return response;
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
        async changePage(next) {
            
        }
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
    }
});
