<template>
    <b-container class="h-100">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-card title="Messages" footer-tag="footer" class="shadow">
                    <b-form @submit.prevent="onSubmit">
                        <p>
                            Register and start using Messages!
                        </p>
                        <b-form-group label="Username" label-for="username">
                            <b-form-input id="username" type="text" v-model="form.username"
                                          placeholder="Enter your username" required>
                            </b-form-input>
                        </b-form-group>
                        <b-form-group label="Email address" label-for="email">
                            <b-form-input id="email" type="email" v-model="form.email"
                                          placeholder="Enter your email" required>
                            </b-form-input>
                        </b-form-group>
                        <b-form-group label="Password" label-for="password">
                            <b-form-input id="password" type="password" v-model="form.password"
                                          placeholder="Enter your password" required>
                            </b-form-input>
                        </b-form-group>
                        <b-button type="submit" variant="primary" class="my-3" block>
                            Register
                        </b-button>
                        <b-button-group class="mt-2 d-flex social-links">
                            <b-button class="w-100" disabled>Register using</b-button>
                            <b-button class="w-100 social-link__google">Google</b-button>
                            <b-button class="w-100 social-link__fb">Facebook</b-button>
                        </b-button-group>
                    </b-form>
                    <div slot="footer">
                        <small>
                            Already on Messages?
                            <router-link to="/login">
                                Login now!
                            </router-link>
                        </small>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    username: '',
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
