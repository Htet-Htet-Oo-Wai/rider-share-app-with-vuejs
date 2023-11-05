import axios from "axios"

const http = () => {
    var http = axios.create({
        baseURL: 'http://127.0.0.1:8000',
        timeout: 1000,
        headers: {
        }
    });
    http.interceptors.request.use((config) => {
        const token = localStorage.getItem('token');
        config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
        return config;
    });
    return http;
}
export default http