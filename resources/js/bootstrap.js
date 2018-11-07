import 'bootstrap-vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueBootstrap from 'bootstrap-vue';
import axios from 'axios';

window.axios = axios;

Vue.use(VueRouter);
Vue.use(VueBootstrap);

// Configure Axios.
const BASE_URL = process.env.MIX_APP_URL;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN']     = document.head.querySelector('meta[name="csrf-token"]').content;
axios.defaults.baseURL                            = `${BASE_URL}`;
if (null != window.localStorage.getItem('accessToken')) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('accessToken');
}
