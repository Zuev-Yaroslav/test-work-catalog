import {router} from "@inertiajs/vue3";
import axios from "axios";
import {HttpStatus} from "http-status-ts";

const api = axios.create();

api.interceptors.request.use((config) => {
    const token: string = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

api.interceptors.response.use((response) => {
        return response;
    },
    (error) => {
        if (error.status === HttpStatus.UNAUTHORIZED) {
            localStorage.removeItem('auth_token')
            router.visit(route('auth.login'));
        }
        return Promise.reject(error);
    })
export default api;
