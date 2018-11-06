<template>
    <b-container class="h-100 pt-1">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-card title="Reset my password">
                    <p class="card-text">
                        Please provide us your new password.
                    </p>
                    <b-alert :show="success" variant="success">
                        Your password has been successfully reset.
                    </b-alert>
                    <form @submit.prevent="onSubmit">
                        <input type="hidden" v-model="form.token"/>
                        <b-form-group label="Token" label-for="token">
                            <b-form-input id="token" type="text" v-model="form.token" disabled
                                          :state="this.error.has('token') ? false : null">
                            </b-form-input>
                            <b-form-invalid-feedback>{{ this.error.get('token') }}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Password" label-for="password">
                            <b-form-input id="password" type="text" v-model="form.password"
                                          placeholder="Enter password"
                                          :state="this.error.has('password') ? false : null">
                            </b-form-input>
                            <b-form-invalid-feedback>{{ this.error.get('password') }}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Password Confirmation" label-for="password-confirmation">
                            <b-form-input id="password-confirmation" type="text" v-model="form.password_confirmation"
                                          placeholder="Enter password again"
                                          :state="this.error.has('password_confirmation') ? false : null">
                            </b-form-input>
                            <b-form-invalid-feedback>{{ this.error.get('password_confirmation') }}
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-button type="submit" variant="primary" :disabled="this.loading" block>
                            Reset my password
                        </b-button>
                    </form>
                    <router-link to="/login">
                        <small>Go to the login page</small>
                    </router-link>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import ApiError from '../helpers/api/ApiError';
    import axios from 'axios';

    export default {
        name: 'ForgotPassword',
        data() {
            return {
                form: {
                    token: this.$route.params.token || '',
                    password: '',
                    password_confirmation: ''
                },
                error: new ApiError(),
                loading: false,
                success: false
            };
        },
        methods: {
            onSubmit() {
                this.loading = true;
                axios.post('/api/password/reset', this.form)
                    .then(() => {
                        this.form.password              = '';
                        this.form.password_confirmation = '';
                        this.error                      = new ApiError();
                        this.success                    = true;
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
