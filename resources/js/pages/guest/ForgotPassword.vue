<template>
    <floating-card>
        <app-title/>
        <h4 class="card-title">
            Forgot my password
        </h4>
        <p class="card-text">
            Give us your email address, we will send you an email to reset your password.
        </p>
        <b-alert :show="success" variant="success">
            Great! We send you an email to reset your password.
        </b-alert>
        <form @submit.prevent="onSubmit">
            <b-form-group label="Email" label-for="email">
                <b-form-input id="email" type="email" v-model="form.email"
                              placeholder="Enter email"
                              :state="this.error.has('email') ? false : null">
                </b-form-input>
                <b-form-invalid-feedback>{{ this.error.get('email') }}</b-form-invalid-feedback>
            </b-form-group>
            <b-button type="submit" variant="primary" :disabled="this.loading" block>
                Send me a reset email
            </b-button>
        </form>
        <router-link to="/login">
            <small>Go to the login page</small>
        </router-link>
    </floating-card>
</template>

<script>
    import ApiError from '../../helpers/api/ApiError';
    import axios from 'axios';

    export default {
        name: 'ForgotPassword',
        data() {
            return {
                form: { email: '' },
                error: new ApiError(),
                loading: false,
                success: false
            };
        },
        methods: {
            onSubmit() {
                this.loading = true;
                axios.post('/api/password/forgot', this.form)
                    .then(() => {
                        this.form.email = '';
                        this.error      = new ApiError();
                        this.success    = true;
                    })
                    .catch((error) => {
                        this.error = new ApiError(error.response.status, error.response.data);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    };
</script>

<style scoped>
</style>
