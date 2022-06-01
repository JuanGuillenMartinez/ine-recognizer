import { defineStore } from "pinia";
import { getAll, get, post, put } from "../helpers/Request";

const baseUrl = "/logs";

export const useLogStore = defineStore("log", {
    state: () => {
        return {
            list: [],
            listFinded: [],
            isLoading: true,
            finded: {},
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
    },
    getters: {
        listFormatted() {
            this.list.map((log) => {
                log.created_at = new Date(log.created_at).toLocaleString();
                return log;
            });
            return this.list;
        },
    },
});
