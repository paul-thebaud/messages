<template>
    <div class="row align-items-center justify-content-center h-100">
        <div class="col-md-6">
            <b-card title="Messages" class="shadow">
                <b-form @submit.prevent="onSubmit">
                    <p>
                        Connect to your account and start typing...
                    </p>
                    <b-form-group label="Email address"
                                  label-for="email">
                        <b-form-input id="email"
                                      type="email"
                                      v-model="form.email"
                                      placeholder="Enter your email"
                                      :state="null"
                                      required>
                        </b-form-input>
                        <b-form-invalid-feedback>
                            Invalid credentials.
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Password"
                                  label-for="password">
                        <b-form-input id="password"
                                      type="password"
                                      v-model="form.password"
                                      placeholder="Enter your password"
                                      :state="null"
                                      required>
                        </b-form-input>
                    </b-form-group>
                    <b-button type="submit"
                              variant="primary"
                              :disabled="false"
                              block>
                        Login
                    </b-button>
                    <b-button-group class="mt-2 d-flex social-links">
                        <b-button class="w-100" disabled>Login using</b-button>
                        <b-button class="w-100 social-link__google">Google</b-button>
                        <b-button class="w-100 social-link__fb">Facebook</b-button>
                    </b-button-group>
                    <div class="text-center mt-1">
                        <router-link to="/register">
                            <small>
                                No account? Click here!
                            </small>
                        </router-link>
                    </div>
                </b-form>
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                }
            };
        }
    };
</script>

<style scoped>
    .social-links {
        border: none;
    }

    .social-links .btn {
        border: none;
    }

    .social-link__google {
        background-color: #d62d20;
    }

    .social-link__fb {
        background-color: #3b5998;
    }
</style>
