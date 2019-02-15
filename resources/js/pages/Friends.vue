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
        <h4>Users</h4>
        <div class="row">
            <div v-for="user in users" class="col my-2">
                <user :user="user" type="user" v-on:add-friend="addFriend"></user>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../helpers/api';
    import User from '../components/friends/User';

    export default {
        name: 'Friends',
        components: {
            User
        },
        data() {
            return {
                userId: this.$store.getters['auth/user'].id,
                friends: [],
                users: [],
                friend_requests: []
            };
        },
        mounted() {
            this.listFriends();
            this.listUsers();
        },
        methods: {
            listFriends() {
                api.index(`users/${this.userId}/friends`)
                    .then((data) => {
                        this.friends         = data.friends;
                        this.friend_requests = data.friend_requests;
                    });
            },
            listUsers() {
                api.index('users')
                    .then(users => {
                        this.users = users;
                    });
            },
            addFriend(user) {
                api.store(`users/${this.userId}/friends`, {
                    user_id: user.id
                })
                    .then(() => {
                        console.log('ok')
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
