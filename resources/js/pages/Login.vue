<template>
    <b-container class="h-100 pt-1">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-card title="Login">
                    <p class="card-text">
                        Welcome back on <strong>Messages</strong>, please enter your credentials to continue typing!
                    </p>
                    <form @submit.prevent="login">
                        <b-form-group label="Email" label-for="email">
                            <b-form-input id="email" type="text" v-model="form.email"
                                          placeholder="Enter email"
                                          :state="this.error.has('email') ? false : null">
                            </b-form-input>
                            <b-form-invalid-feedback>{{ this.error.get('email') }}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Password" label-for="password">
                            <b-form-input id="password" type="text" v-model="form.password"
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
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import router from '../router';
    import ApiError from '../helpers/api/ApiError';
    import SocialButtons from '../components/SocialButtons';

    export default {
        name: 'Login',
        components: {
            SocialButtons
        },
        data() {
            return {
                form: { driver: 'password', email: '', password: '' },
                error: new ApiError(),
                loading: false
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
