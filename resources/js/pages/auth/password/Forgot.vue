<template>
    <b-container class="h-100">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-card title="Messages" footer-tag="footer" class="shadow">
                    <b-form @submit.prevent="onSubmit">
                        <p>
                            Give us your email address, we will send you a password reset link.
                        </p>
                        <b-form-group label="Email address" label-for="email">
                            <b-form-input id="email" type="email" v-model="form.email"
                                          placeholder="Enter your email" required>
                            </b-form-input>
                        </b-form-group>
                        <b-button type="submit" variant="primary" class="my-3" block>
                            Send me a reset link
                        </b-button>
                    </b-form>
                    <div slot="footer">
                        <small>
                            I remembered my password.
                            <router-link to="/register">
                                Bring me to the login page!
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
                    email: ''
                }
            };
        }
    };
</script>
