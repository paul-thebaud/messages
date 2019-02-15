<template>
    <floating-card>
        <app-title/>
        <h4 class="card-title">
            Login
        </h4>
        <p class="card-text">
            Welcome back on <strong>Messages</strong>, please enter your credentials to continue typing!
        </p>
        <b-alert :show="verified" variant="success" dismissible>
            Email address verified! Enter your credentials to start using Messages.
        </b-alert>
        <form @submit.prevent="login">
            <b-form-group label="Email" label-for="email">
                <b-form-input id="email" type="email" v-model="form.email"
                              placeholder="Enter email"
                              :state="this.error.has('email') ? false : null">
                </b-form-input>
                <b-form-invalid-feedback>{{ this.error.get('email') }}</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Password" label-for="password">
                <b-form-input id="password" type="password" v-model="form.password"
                              placeholder="••••••"
                              :state="this.error.has('password') ? false : null">
                </b-form-input>
                <router-link to="/password/forgot" class="mt-2">
                    <small>Forgot your password?</small>
                </router-link>
                <b-form-invalid-feedback>{{ this.error.get('password') }}</b-form-invalid-feedback>
            </b-form-group>
            <b-button type="submit" variant="primary" :disabled="this.loading" block>
                Login
            </b-button>
        </form>
        <div class="my-2">
            <social-buttons></social-buttons>
        </div>
        <router-link to="/register">
            <small>Want an account? Create it here.</small>
        </router-link>
    </floating-card>
</template>

<script>
    import router from '../../router/index';
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'Login',
        data() {
            return {
                form: { driver: 'password', email: '', password: '' },
                error: new ApiError(),
                loading: false,
                verified: Object.hasOwnProperty.call(this.$route.query, 'verified')
            };
        },
        methods: {
            login() {
                this.loading = true;
                this.$store.dispatch('auth/login', this.form)
                    .then(() => {
                        this.loading = false;
                        router.push({ name: 'Conversations' });
                    })
                    .catch((error) => {
                        this.error   = error;
                        this.loading = false;
                    });
            }
        }
    };
</script>

<style scoped>
</style>
