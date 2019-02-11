import 'bootstrap-vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueBootstrap from 'bootstrap-vue';
import VueChatScroll from 'vue-chat-scroll';
import GiphyClient from 'giphy-js-sdk-core';
import Echo from 'laravel-echo';
import axios from 'axios';

window.axios = axios;

Vue.use(VueRouter);
Vue.use(VueBootstrap);
Vue.use(VueChatScroll);

window.giphyClient = GiphyClient(process.env.MIX_GIPHY_KEY);

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
    wsHost: process.env.MIX_WS_HOST,
    wsPort: process.env.MIX_WS_PORT,
    encrypted: process.env.MIX_WS_ENCRYPTION,
    disableStats: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + window.localStorage.getItem('accessToken')
        }
    }
});
Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.clickOutsideEvent = function (event) {
            // here I check that click was outside the el and his childrens
            if (!(el == event.target || el.contains(event.target))) {
                // and if it did, call method provided in attribute value
                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unbind: function (el) {
        document.body.removeEventListener('click', el.clickOutsideEvent);
    }
});
