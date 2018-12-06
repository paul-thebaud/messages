<template>
    <div class="friends">
        <h3>Friends</h3>
        <p>
            Here, you can send friend request, accept yours and manage your friends list.
        </p>
        <div class="row">
            <div class="col-sm-8">
                <h4>Friends</h4>
                <p v-if="this.friends.length <= 0">
                    No friend in your list.
                </p>
            </div>
            <div class="col-sm-4">
                <h4>Friend requests</h4>
                <p v-if="this.friends.length <= 0">
                    No friend request received.
                </p>
            </div>
        </div>
        <h4>Adding friends</h4>

    </div>
</template>

<script>
    import api from '../helpers/api';

    export default {
        name: 'Friends',
        data() {
            return {
                userId: this.$store.getters['auth/user'].id,
                friends: [],
                friend_requests: []
            };
        },
        mounted() {
            this.listFriends();
        },
        methods: {
            listFriends() {
                api.index(`users/${this.userId}/friends`)
                    .then((data) => {
                        this.friends         = data.friends;
                        this.friend_requests = data.friend_requests;
                    });
            }
        }
    };
</script>

<style lang="scss" scoped>
    .friends {
        height: 100%;
        margin-left: 80px;
        padding: 1rem;
        overflow-y: scroll;
    }
</style>
