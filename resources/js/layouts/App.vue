<template>
    <div id="app">
        <sidebar v-if="this.$store.getters['auth/isLogged']"></sidebar>
        <div class="sidebar-wrapper" :class="{ 'sidebar-active': this.$store.getters['auth/isLogged'] }">
            <router-view v-on:loading-toggle="loadingToggle"></router-view>
            <spinner v-if="loading"></spinner>
        </div>
    </div>
</template>

<script>
    import Spinner from './Spinner';
    import Sidebar from './Sidebar';
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
            Spinner,
            Sidebar
        }
    };
</script>

<style lang="scss" scoped>
    #app {
        height: 100%;
        .sidebar-wrapper {
            height: 100%;
            &.sidebar-active {
                margin-left: 80px;
            }
        }
    }
</style>
