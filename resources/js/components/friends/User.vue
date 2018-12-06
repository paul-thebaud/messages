<template>
    <div class="card">
        <div class="p-2 text-center">
            <div class="pb-2">
                <b>{{ user.username }}</b>
            </div>
            <button v-if="type === 'user'" type="button" class="btn btn-primary btn-block" @click="addFriend">
                Add friend
            </button>
            <button v-if="type === 'friend'" type="button" class="btn btn-primary btn-block" @click="deleteFriend">
                Delete
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'User',
        props: {
            user: {
                required: true,
                type: Object,
                props: {
                    id: {
                        required: true,
                        type: String
                    },
                    username: {
                        required: true,
                        type: String
                    }
                }
            },
            type: {
                required: true,
                type: String
            }
        },
        methods: {
            addFriend() {
                this.$emit('add-friend', this.user);
            },
            deleteFriend() {
                this.$emit('delete-friend', this.user);
            }
        }
    };
</script>

<style scoped>

</style>
