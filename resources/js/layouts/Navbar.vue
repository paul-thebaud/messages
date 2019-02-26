<template>
    <div class="navbar d-flex">
        <div class="flex-grow-1">
            <router-link class="navbar-title" to="/conversations">
                <img src="/ms-icon-310x310.png" alt="Messages App Logo">
                Messages
            </router-link>
        </div>
        <router-link class="btn btn-primary mr-2" to="/profile" v-b-tooltip.hover title="Profile">
            <account-icon/>
        </router-link>
        <a href="#" class="btn btn-danger" @click.prevent="logout" v-b-tooltip.hover title="Logout">
            <logout-icon/>
        </a>
    </div>
</template>

<script>
    import AccountIcon from 'vue-material-design-icons/Account';
    import LogoutIcon from 'vue-material-design-icons/Logout';

    export default {
        name: 'Sidebar',
        components: {
            AccountIcon,
            LogoutIcon,
        },
        methods: {
            logout() {
                this.$store.dispatch('auth/logout')
                    .finally(() => {
                        this.$router.push({ name: 'Login' });
                    });
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../scss/variables';

    .navbar {
        background-color: $body-bg;
        position: fixed;
        top: 0;
        left: 0;
        height: 55px;
        width: 100%;
        border-right: 1px solid $border-color;
        .navbar-title {
            line-height: 38px;
            vertical-align: middle;
            color: $text-muted;
            font-weight: bold;
            font-size: 1.2rem;
            img {
                max-height: 38px;
            }
            &:hover {
                text-decoration: none;
                color: $gray-800;
            }
        }
    }
</style>
