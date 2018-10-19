<template>
    <b-form @submit.prevent="onSubmit">
        <h1>Forgot your password?</h1>
        <p>
            Just fill the form with your email address, we will send you a reset link.
        </p>
        <b-form-group label="Email address"
                      label-for="email">
            <b-form-input id="email"
                          type="email"
                          v-model="form.email"
                          placeholder="jane.doe@example.com"
                          :state="emailState"
                          required>
            </b-form-input>
            <b-form-invalid-feedback>
                {{ this.emailState === null || this.errors.email[0] }}
            </b-form-invalid-feedback>
        </b-form-group>
        <b-button type="submit"
                  variant="primary"
                  :disabled="loading"
                  block>
            Send me a reset link
        </b-button>
    </b-form>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                form: {
                    email: ''
                },
                errors: {}
            };
        },
        computed: {
            emailState() {
                return Object.keys(this.errors).includes('email') ? false : null;
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                axios.post('/api/auth/password/forgot', this.form)
                    .then(response => {
                        console.log(response);
                        this.form.email = '';
                        this.errors     = {};
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            console.log(error.response);
                        }
                    })
                    .finally(() => this.loading = false);
            }
        }
    };
</script>
