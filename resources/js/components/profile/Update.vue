<template>
    <div class="card my-4">
        <div class="card-body">
            <form @submit.prevent="updateUser">
                <b-form-group label="Username" label-for="username">
                    <b-form-input id="username" type="text" v-model="form.username"
                                  placeholder="Enter username"
                                  :state="this.error.has('username') ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback>{{ this.error.get('username') }}</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Password" label-for="password">
                    <b-form-input id="password" type="password" v-model="form.password"
                                  placeholder="Enter password"
                                  :state="this.error.has('password') ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback>{{ this.error.get('password') }}</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Password confirmation" label-for="password_confirmation">
                    <b-form-input id="password_confirmation" type="password" v-model="form.password_confirmation"
                                  placeholder="Enter password again"
                                  :state="this.error.has('password_confirmation') ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback>{{ this.error.get('password_confirmation') }}</b-form-invalid-feedback>
                </b-form-group>
                <div>
                    <button type="button" @click="deleteUser" class="btn btn-danger float-right">
                        Delete my account and all my data
                    </button>
                    <button type="submit" class="btn btn-primary" :disabled="this.loading">
                        Update your profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'Update',
        data() {
            const user = this.$store.getters['auth/user'];
            return {
                form: {
                    id: user.id,
                    username: user.username,
                    password: null,
                    password_confirmation: null
                },
                error: new ApiError(),
                loading: false
            };
        },
        methods: {
            updateUser() {
                this.loading = true;
                this.$store.dispatch('auth/update', this.form)
                    .then(() => {
                        this.error = new ApiError();
                    })
                    .catch((error) => this.error = error)
                    .finally(() => this.loading = false);
            },
            deleteUser() {
                this.$store.dispatch('auth/delete')
                    .finally(() => {
                        this.$router.push({ name: 'Login' });
                    });
            }
        }
    };
</script>

<style scoped>
</style>
