/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";

import Vue from 'vue';
import Vuex from 'vuex';
import 'es6-promise/auto';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';

import routes from './routes';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(Vuex);

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
