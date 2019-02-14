import axios from 'axios';
import api from '../helpers/api';

const TYPES = {
    LOGIN: 'LOGIN',
    FETCH_USER: 'FETCH_USER',
    UPDATE: 'UPDATE',
    LOGOUT: 'LOGOUT'
};

const state = {
    logged: !!window.localStorage.getItem('accessToken'),
    user: null,
    tokenId: window.localStorage.getItem('tokenId')
};

const mutations = {
    [TYPES.LOGIN](state, tokenId) {
        state.logged  = true;
        state.tokenId = tokenId;
    },
    [TYPES.FETCH_USER](state, user) {
        state.user = user;
    },
    [TYPES.UPDATE](state, user) {
        state.user.username = user.username;
    },
    [TYPES.LOGOUT](state) {
        state.logged  = false;
        state.tokenId = null;
        window.localStorage.removeItem('accessToken');
        window.localStorage.removeItem('userId');
        window.localStorage.removeItem('tokenId');
        delete axios.defaults.headers.common['Authorization'];
    }
};

const getters = {
    isLogged: state => state.logged,
    user: state => state.user,
    tokenId: state => state.tokenId
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
                    commit(TYPES.LOGIN, data.token.id);
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

    logout({ commit, state }) {
        return new Promise((resolve) => {
            axios.delete(`/api/tokens/${state.tokenId}`)
                .finally(() => {
                    commit(TYPES.LOGOUT);
                    resolve();
                });
        });
    },

    update({ commit, state }, user) {
        return new Promise((resolve, reject) => {
            api.update('users', state.user.id, user)
                .then(() => {
                    commit(TYPES.UPDATE, user);
                    resolve();
                })
                .catch(error => reject(error));
        });
    },

    delete({ commit, state }) {
        return new Promise((resolve) => {
            api.destroy('users', state.user.id)
                .finally(() => {
                    commit(TYPES.LOGOUT);
                    resolve();
                });
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
