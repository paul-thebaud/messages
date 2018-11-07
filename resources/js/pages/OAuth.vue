<template>
    <b-container class="h-100">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-alert :show="error" variant="danger" class="text-center">
                    <p>
                        Invalid code given. Please retry logging in.
                    </p>
                    <b-button variant="danger" to="/login">&larr; Go to login page</b-button>
                </b-alert>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import router from '../router';

    export default {
        name: 'OAuth',
        data() {
            return {
                driver: this.$route.params.driver,
                code: this.$route.query.code,
                error: false
            };
        },
        mounted() {
            this.$emit('loading-toggle');
            this.$store.dispatch('auth/login', { driver: this.driver, auth_code: this.code })
                .then(() => {
                    this.$emit('loading-toggle');
                    router.push({ name: 'Conversations' });
                })
                .catch(() => {
                    this.$emit('loading-toggle');
                    this.error = true;
                });
        }
    };
</script>

<style scoped>

</style>
