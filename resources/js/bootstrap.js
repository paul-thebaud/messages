import 'bootstrap-vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueBootstrap from 'bootstrap-vue';
import VueChatScroll from 'vue-chat-scroll';
import axios from 'axios';
import Echo from 'laravel-echo';

window.axios = axios;

Vue.use(VueRouter);
Vue.use(VueBootstrap);
Vue.use(VueChatScroll);

// Configure Axios.
const BASE_URL = process.env.MIX_APP_URL;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN']     = document.head.querySelector('meta[name="csrf-token"]').content;
axios.defaults.baseURL                            = BASE_URL;
if (null != window.localStorage.getItem('accessToken')) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('accessToken');
}


window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + window.localStorage.getItem('accessToken')
        }
    }
});
