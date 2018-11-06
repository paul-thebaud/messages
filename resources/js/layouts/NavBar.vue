<template>
    <b-navbar toggleable="md" class="bg-white" v-if="this.$store.getters['auth/isLogged']">
        <b-navbar-brand to="/">Messages</b-navbar-brand>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse is-nav id="nav-collapse">
            <b-navbar-nav class="ml-auto">
                <b-nav-item-dropdown :text="this.$store.getters['auth/user'].username" right>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
                    <b-dropdown-item @click.prevent="logout">Logout</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
    import router from '../router';

    export default {
        name: 'NavBar',
        methods: {
            logout() {
                this.$store.dispatch('auth/logout')
                    .finally(() => {
                        router.push({ name: 'Login' });
                    });
            }
        }
    };
</script>

<style scoped>

</style>
