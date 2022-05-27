import axios from "axios";
import { pathApi } from "../env";

const instance = {
    getInstance() {
        const token = localStorage.getItem('token')
        let config = {
            baseURL: pathApi,
        };
        if (token) {
            config.headers = {
                Authorization: `Bearer ${token}`
            }
        }
        return axios.create(config);
    },
};

export default instance