import axios from 'axios';
import ApiError from './ApiError';
import router from '../../router';
import vuexStore from '../../store';

export default {
    index(endpoint, params = {}) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/${endpoint}`, { params })
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(this._onError(new ApiError(error.response.status, error.response.data)));
                });
        });
    },
    store(endpoint, data) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/${endpoint}`, data)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(this._onError(new ApiError(error.response.status, error.response.data)));
                });
        });
    },
    show(endpoint, id) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/${endpoint}/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(this._onError(new ApiError(error.response.status, error.response.data)));
                });
        });
    },
    update(endpoint, id, data) {
        return new Promise((resolve, reject) => {
            axios.put(`/api/${endpoint}/${id}`, data)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(this._onError(new ApiError(error.response.status, error.response.data)));
                });
        });
    },
    destroy(endpoint, id) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/${endpoint}/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(this._onError(new ApiError(error.response.status, error.response.data)));
                });
        });
    },
    _onError(error) {
        if (error.isInvalid()) {
            return error;
        }
        if (error.isUnauthenticated()) {
            vuexStore.dispatch('auth/logout')
                .finally(() => router.push({ name: 'Login' }));
        }
        // TODO Manage forbidden and unknown.
    }
};
