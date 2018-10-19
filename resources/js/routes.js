import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";

import auth from "./services/auth";

const Hello = {template: '<div>Hello foo</div>'};

const requireAuth = (to, from, next) => {
    if (auth.guest()) {
        next({
            path: '/login',
            query: {redirect: to.fullPath}
        });
    } else {
        next();
    }
};

export default [
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/', component: Hello, beforeEnter: requireAuth}
];
