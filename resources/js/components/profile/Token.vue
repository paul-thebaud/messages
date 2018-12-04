<template>
    <div class="card my-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <code class="font-weight-bold text-dark">{{ id }}</code>
                    <code class="text-dark" style="white-space: pre-wrap;">{{ name }}</code>
                </div>
                <div class="col-sm-4 text-right">
                    <p v-if="this.$store.getters['auth/tokenId'] === id" class="text-success">
                        Token for this session
                    </p>
                    <p>
                        {{ createdAtDisplay }}
                    </p>
                    <b-btn variant="danger" class="mt-2" v-on:click="deleteToken">Delete</b-btn>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        name: 'Token',
        props: {
            id: {
                required: true,
                type: String
            },
            name: {
                required: true,
                type: String
            },
            createdAt: {
                required: true,
                type: String
            }
        },
        methods: {
            deleteToken() {
                this.$emit('delete-token', this.id);
            }
        },
        computed: {
            createdAtDisplay() {
                const date = moment(this.createdAt);
                return `Created the ${date.format('YYYY-MM-DD')} at ${date.format('h:mm')}`;
            }
        }
    };
</script>

<style scoped>
</style>
