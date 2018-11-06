import Vue from 'vue';
import './bootstrap';
import router from './router';
import store from './store';
import App from './layouts/App.vue';

new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: { App }
});
