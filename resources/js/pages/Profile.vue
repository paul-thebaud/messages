<template>
    <div class="profile">
        <div class="settings">
            <h3>Profile</h3>
            <p>
                Here, you can update or delete your profile.
            </p>
            <update></update>
        </div>
        <div class="security">
            <h3>Authentication and security</h3>
            <p>
                Here, you can see which devices are using the Messages application, and revoke access to any of them.
            </p>
            <tokens></tokens>
        </div>
    </div>
</template>

<script>
    import Update from '../components/profile/Update';
    import Tokens from '../components/profile/Tokens';

    export default {
        name: 'Profile',
        components: {
            Update,
            Tokens
        }
    };
</script>

<style lang="scss" scoped>
    .profile {
        height: 100%;
        margin-left: 80px;
        padding: 1rem;
        overflow-y: scroll;
    }
</style>
