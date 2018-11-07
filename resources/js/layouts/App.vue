<template>
    <div id="app" class="h-100">
        <nav-bar v-if="!loading"></nav-bar>
        <router-view v-on:loading-toggle="loadingToggle"></router-view>
        <spinner v-if="loading"></spinner>
    </div>
</template>

<script>
    import NavBar from './NavBar';
    import Spinner from './Spinner';
    import router from '../router';

    export default {
        mounted() {
            if (!this.$store.getters['auth/isLogged']) {
                this.loadingToggle();
                return;
            }
            this.$store.dispatch('auth/user')
                .catch(() => {
                    router.push({ name: 'Login' });
                })
                .finally(() => {
                    this.loadingToggle();
                });
        },
        data() {
            return {
                loading: true
            };
        },
        methods: {
            loadingToggle() {
                this.loading = !this.loading;
            }
        },
        components: {
            NavBar,
            Spinner
        }
    };
</script>

<style scoped>
</style>
