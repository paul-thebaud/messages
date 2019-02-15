<template>
    <div id="app">
        <navbar v-if="this.$store.getters['auth/isLogged']"></navbar>
        <div class="navbar-wrapper">
            <router-view v-if="!initialLoading" v-on:loading-toggle="loadingToggle"></router-view>
            <spinner v-if="loading"></spinner>
        </div>
    </div>
</template>

<script>
    import Spinner from './Spinner';
    import Navbar from './Navbar';
    import router from '../router';

    export default {
        mounted() {
            if (!this.$store.getters['auth/isLogged']) {
                this.initialLoading = false;
                this.loadingToggle();
                return;
            }
            this.$store.dispatch('auth/user')
                .catch(() => {
                    router.push({ name: 'Login' });
                })
                .finally(() => {
                    this.initialLoading = false;
                    this.loadingToggle();
                });
        },
        data() {
            return {
                initialLoading: true,
                loading: true
            };
        },
        methods: {
            loadingToggle() {
                this.loading = !this.loading;
            }
        },
        components: {
            Spinner,
            Navbar
        }
    };
</script>

<style lang="scss" scoped>
    #app {
        height: 100%;
        .navbar-wrapper {
            padding-top: 55px;
            height: 100%;
        }
    }
</style>
