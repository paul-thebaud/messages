import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";
import ForgotPassword from "./pages/auth/password/Forgot";

import auth from "./services/auth";

const Hello = {template: '<div>Hello foo</div>'};

const requireAuth = (to, from, next) => {
    if (auth.guest()) {
        next({
            path: '/login',
        });
    } else {
        next();
    }
};

export default [
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/password/forgot', component: ForgotPassword},
    {path: '/', component: Hello, beforeEnter: requireAuth}
];
