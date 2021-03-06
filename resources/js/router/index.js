import VueRouter from 'vue-router';
import store from '../store';
import Login from '../pages/guest/Login';
import Register from '../pages/guest/Register';
import ForgotPassword from '../pages/guest/ForgotPassword';
import ResetPassword from '../pages/guest/ResetPassword';
import OAuth from '../pages/guest/OAuth';
import Conversations from '../pages/conversations/Conversations';
import Conversation from '../pages/conversations/Conversation';
import ConversationDetails from '../pages/conversations/ConversationDetails';
import NoConversation from '../pages/conversations/NoConversation';
import Profile from '../pages/Profile';
import ConversationCreate from '../pages/conversations/ConversationCreate';

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', redirect: '/conversations' },
        {
            path: '/conversations',
            name: 'Conversations',
            component: Conversations,
            meta: {
                auth: true
            },
            children: [
                {
                    path: '',
                    name: 'NoConversation',
                    component: NoConversation,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: 'create',
                    name: 'ConversationCreate',
                    component: ConversationCreate,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: ':conversation_id',
                    name: 'Conversation',
                    component: Conversation,
                    meta: {
                        auth: true
                    }
                },
                {
                    path: ':conversation_id/details',
                    name: 'ConversationDetails',
                    component: ConversationDetails,
                    meta: {
                        auth: true
                    }
                }
            ]
        },
        {
            path: '/profile',
            name: 'Profile',
            component: Profile,
            meta: {
                auth: true
            }
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
