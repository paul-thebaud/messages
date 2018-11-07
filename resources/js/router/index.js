import VueRouter from 'vue-router';
import store from '../store';
import Conversations from '../pages/Conversations';
import Login from '../pages/Login';
import Register from '../pages/Register';
import ForgotPassword from '../pages/ForgotPassword';
import ResetPassword from '../pages/ResetPassword';
import OAuth from '../pages/OAuth';

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/conversations',
            name: 'Conversations',
            component: Conversations,
            meta: {
                auth: true
            },
            alias: '/'
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
            meta: {
                auth: false
            }
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: {
                auth: false
            }
        },
        {
            path: '/password/forgot',
            name: 'ForgotPassword',
            component: ForgotPassword,
            meta: {
                auth: false
            }
        },
        {
            path: '/password/reset/:token',
            name: 'ResetPassword',
            component: ResetPassword,
            meta: {
                auth: false
            }
        },
        {
            path: '/oauth/:driver',
            name: 'OAuth',
            component: OAuth,
            meta: {
                auth: false
            }
        },
        {
            path: '*',
            redirect: '/conversations'
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (!store.getters['auth/isLogged'] && to.meta.auth) {
        return next('/login');
    }
    if (store.getters['auth/isLogged'] && (to.name === 'Login' || to.name === 'Register')) {
        return next('/');
    }
    next();
});

export default router;
