<template>
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
</template>

<script>
    import moment from 'moment';
    import api from '../../helpers/api';
    import Token from './Token';

    export default {
        name: 'Tokens',
        components: {
            Token
        },
        data() {
            return {
                tokens: []
            };
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

<style scoped>
</style>
