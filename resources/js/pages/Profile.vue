<template>
    <div class="profile">
        <div class="settings">
            <h3>Profile</h3>
            <p>
                Here, you can update your profile information.
            </p>
            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                           id="username"
                           class="form-control"
                           placeholder="Enter your username"
                           v-model="user.username"/>
                </div>
            </form>
        </div>
        <div class="security">
            <h3>Authentication and security</h3>
            <p>
                Here, you can see which devices are using the Messages application, and revoke access to any of them.
            </p>
            <div class="tokens">
                <token
                        v-for="token in tokens"
                        :key="token.id"
                        :id="token.id"
                        :name="token.name"
                        :created-at="token.created_at"
                        v-on:delete-token="deleteToken"
                ></token>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import api from '../helpers/api';
    import Token from '../components/profile/Token';

    export default {
        name: 'Profile',
        data() {
            return {
                user: this.$store.getters['auth/user'],
                tokens: []
            };
        },
        components: {
            Token
        },
        mounted() {
            this.listTokens();
        },
        methods: {
            listTokens() {
                const currentTokenId = this.$store.getters['auth/tokenId'];
                api.index('tokens')
                    .then(tokens => {
                        // Sort the token to make the current one appears as first,
                        // and else on datetime.
                        this.tokens = tokens.sort((left, right) => {
                            if (currentTokenId === left.id) {
                                return -1;
                            }
                            return moment(right.created_at).diff(left.created_at);
                        });
                    });
            },
            deleteToken(id) {
                // If the deleted token correspond to the current session,
                // close session.
                if (id === this.$store.getters['auth/tokenId']) {
                    this.$store.dispatch('auth/logout')
                        .finally(() => {
                            this.$router.push({ name: 'Login' });
                        });
                    return;
                }
                api.destroy('tokens', id)
                    .finally(() => {
                        // Else, just remove it from list.
                        this.tokens = this.tokens.filter((token) => token.id !== id);
                    });
            }
        }
    };
</script>

<style lang="scss" scoped>
    .profile {
        height: 100%;
        margin-left: 80px;
        padding: 0.5rem;
        overflow-y: scroll;
    }
</style>
