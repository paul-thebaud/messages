<template>
    <b-container class="mt-2">
        <b-card title="My information">
            <p>
                Here you can update your username, request a password update or delete your account.
            </p>
            <form @submit.prevent="updateUser">
                <b-form-group label="Username" label-for="username">
                    <b-input-group>
                        <b-form-input id="username" type="text" v-model="user.username"
                                      placeholder="Enter username"
                                      :state="this.error.has('username') ? false : null">
                        </b-form-input>
                        <b-input-group-append>
                            <b-btn variant="primary">Save</b-btn>
                        </b-input-group-append>
                    </b-input-group>
                    <b-form-invalid-feedback>{{ this.error.get('username') }}</b-form-invalid-feedback>
                </b-form-group>
            </form>
        </b-card>
    </b-container>
</template>

<script>
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'Profile',
        data() {
            return {
                user: this.$store.getters['auth/user'],
                error: new ApiError()
            };
        },
        mounted() {
            // this.fetch();
        },
        methods: {
            updateUser() {
            }
        }
    };
</script>

<style scoped>

</style>
