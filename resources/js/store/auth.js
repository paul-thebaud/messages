import axios from 'axios';
import api from '../helpers/api';

const TYPES = {
    LOGIN: 'LOGIN',
    FETCH_USER: 'FETCH_USER',
    LOGOUT: 'LOGOUT'
};

const state = {
    logged: !!window.localStorage.getItem('accessToken'),
    user: null
};

const mutations = {
    [TYPES.LOGIN](state) {
        state.logged = true;
    },
    [TYPES.FETCH_USER](state, user) {
        state.user = user;
    },
    [TYPES.LOGOUT](state) {
        state.logged = false;
        window.localStorage.removeItem('accessToken');
        window.localStorage.removeItem('userId');
        window.localStorage.removeItem('tokenId');
        delete axios.defaults.headers.common['Authorization'];
    }
};

const getters = {
    isLogged: state => state.logged,
    user: state => state.user
};

const actions = {
    user({ commit }) {
        return new Promise((resolve, reject) => {
            const userId = window.localStorage.getItem('userId');
            api.show('users', userId)
                .then((user) => {
                    commit(TYPES.FETCH_USER, user);
                    resolve(user);
                })
                .catch(() => {
                    commit(TYPES.LOGOUT);
                    reject();
                });
        });
    },

    login({ commit, dispatch }, credentials) {
        return new Promise((resolve, reject) => {
            api.store('tokens', credentials)
                .then((data) => {
                    commit(TYPES.LOGIN);
                    window.localStorage.setItem('accessToken', data.access_token);
                    window.localStorage.setItem('userId', data.token.user_id);
                    window.localStorage.setItem('tokenId', data.token.id);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;
                    dispatch('user')
                        .then(() => {
                            resolve();
                        });
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },

    logout({ commit }) {
        return new Promise((resolve) => {
            commit(TYPES.LOGOUT);
            resolve();
        });
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
};
