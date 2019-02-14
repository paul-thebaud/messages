<template>
    <div class="card my-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <code class="text-dark" style="white-space: pre-wrap;">{{ name }}</code>
                </div>
                <div class="col-sm-4 text-right">
                    <p>
                        {{ createdAtDisplay }}
                    </p>
                    <b-btn variant="danger"
                           @click="deleteToken"
                           v-b-tooltip.hover
                           title="Delete this token"
                    >
                        <trash-can-icon/>
                    </b-btn>
                    <p v-if="this.$store.getters['auth/tokenId'] === id"
                       class="mt-3 mb-0 text-success"
                    >
                        Token for this session
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import TrashCanIcon from 'vue-material-design-icons/TrashCan';

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
        components: {
            TrashCanIcon
        },
        methods: {
            deleteToken() {
                this.$emit('delete-token', this.id);
            }
        },
        computed: {
            createdAtDisplay() {
                const date = moment.utc(this.createdAt);
                return `Created the ${date.format('YYYY-MM-DD')} at ${date.format('h:mm')}`;
            }
        }
    };
</script>

<style scoped>
</style>
