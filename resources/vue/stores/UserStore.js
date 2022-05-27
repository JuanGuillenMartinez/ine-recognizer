import { defineStore } from "pinia";
import { getAll, get, post } from "../helpers/Request";
import headers from "../helpers/TableHeaders/Folder"
const baseUrl = "/folders";
export const useFolderStore = defineStore("folder", {
    state: () => {
        return {
            list: [],
            listFinded: [],
            isLoading: true,
            finded: {},
            tableHeaders: headers,
            selected: {},
        };
    },
    actions: {
        async all() {
            this.isLoading = true;
            const response = await getAll(baseUrl);
            const { data } = response;
            this.list = data;
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
        }
    },
});
