<template>
    <div class="sidebar">
        <router-link class="sidebar-link" v-b-tooltip.hover.right
                     title="Conversations" to="/conversations">
            Convs
        </router-link>
        <router-link class="sidebar-link" v-b-tooltip.hover.right
                     title="Friends" to="/friends">
            Friends
        </router-link>
        <router-link class="sidebar-link" v-b-tooltip.hover.right
                     title="Profile" to="/profile">
            Profile
        </router-link>
        <a href="#" class="sidebar-link" v-b-tooltip.hover.right
           title="Logout" @click.prevent="logout">
            Logout
        </a>
    </div>
</template>

<script>
    import router from '../router/index';

    export default {
        name: 'Sidebar',
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

<style lang="scss" scoped>
    @import '../../scss/variables';

    .sidebar {
        display: flex !important;
        align-items: flex-start !important;
        flex-direction: column !important;
        float: left;
        height: 100%;
        width: 80px;
        max-width: 80px;
        border-right: 1px solid $border-color;
        .sidebar-link {
            text-align: center;
            vertical-align: middle;
            margin: 9px 9px 0 9px;
            height: 60px;
            width: 60px;
            line-height: 60px;
            background-color: white;
            border-radius: 50%;
            border: 1px solid $border-color;
            color: $gray-900;
            text-decoration: none;
            &:first-child {
                margin-bottom: auto;
            }
            &:last-child {
                margin-bottom: 9px;
            }
            &:hover {
                background-color: $border-color;
            }
        }
    }
</style>
