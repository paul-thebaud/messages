/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";

import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';

import ExampleComponent from "./components/ExampleComponent.vue";
import ForgotComponent from "./components/auth/password/ForgotComponent.vue";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueRouter);
Vue.use(BootstrapVue);

Vue.component('example-component', ExampleComponent);
Vue.component('password-forgot-component', ForgotComponent);
const Hello = {template: '<div>foo</div>'};
const Join  = {template: '<div>bar</div>'};

const routes = [
    {path: '/', component: Hello},
    {path: '/join', component: Join}
];

const router = new VueRouter(
    {
        mode: 'history',
        routes: routes
    }
);

const app = new Vue({
    el: '#app',
    router
});
