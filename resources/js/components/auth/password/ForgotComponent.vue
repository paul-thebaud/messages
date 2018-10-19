<template>
    <b-form @submit.prevent="onSubmit" validated>
        <h1>Forgot your password?</h1>
        <p>
            Just fill the form with your email address, we will send you a reset link.
        </p>
        <b-form-group label="Email address"
                      label-for="email">
            <b-form-input id="email"
                          type="email"
                          v-model="email"
                          placeholder="jane.doe@example.com"
                          required>
            </b-form-input>
            <b-form-invalid-feedback>
                Please enter a valid email address.
            </b-form-invalid-feedback>
        </b-form-group>
        <b-button type="submit"
                  variant="primary"
                  block>
            Send me a reset link
        </b-button>
    </b-form>
</template>

<script>
    export default {
        data() {
            return {
                email: ''
            };
        },
        methods: {
            onSubmit() {
                console.log(this.email);
            }
        }
    };
</script>
