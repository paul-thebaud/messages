<template>
    <floating-card title="Register">
        <p class="card-text">
            Welcome on <strong>Messages</strong>, please enter your information before starting typing!
        </p>
        <b-alert :show="success" variant="success" dismissible>
            Account created! Check your email inbox to validate it before logging in.
        </b-alert>
        <form @submit.prevent="register">
            <b-form-group label="Username" label-for="username">
                <b-form-input id="username" type="text" v-model="form.username"
                              placeholder="Enter username"
                              :state="this.error.has('username') ? false : null">
                </b-form-input>
                <b-form-invalid-feedback>{{ this.error.get('username') }}</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Email" label-for="email"
                          description="We'll never share your email with anyone else.">
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
                <b-form-invalid-feedback>{{ this.error.get('password') }}</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Password Confirmation" label-for="password-confirmation">
                <b-form-input id="password-confirmation" type="password"
                              v-model="form.password_confirmation" placeholder="••••••"
                              :state="this.error.has('password_confirmation') ? false : null">
                </b-form-input>
                <b-form-invalid-feedback>
                    {{ this.error.get('password_confirmation') }}
                </b-form-invalid-feedback>
            </b-form-group>
            <b-btn type="submit" variant="primary" :disabled="this.loading" block>
                Register
            </b-btn>
        </form>
        <div class="my-2">
            <social-buttons></social-buttons>
        </div>
        <router-link to="/login">
            <small>Already got an account? Login here.</small>
        </router-link>
    </floating-card>
</template>

<script>
    import api from '../../helpers/api/index';
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'Register',
        data() {
            return {
                form: {
                    driver: 'password',
                    username: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                error: new ApiError(),
                loading: false,
                success: false
            };
        },
        methods: {
            register() {
                this.loading = true;
                api.store('users', this.form)
                    .then(() => {
                        this.error = new ApiError();

                        this.form.username              = '';
                        this.form.email                 = '';
                        this.form.password              = '';
                        this.form.password_confirmation = '';

                        this.loading = false;
                        this.success = true;
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
