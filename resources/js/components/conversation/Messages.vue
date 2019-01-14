<template>
    <div class="p-3">
        <message-group v-for="(messageGroup, index) in messageGroups"
                       :key="index"
                       :user="messageGroup.user"
                       :messages="messageGroup.messages"
        ></message-group>
    </div>
</template>

<script>
    import MessageGroup from './MessageGroup';

    export default {
        name: 'Messages',
        props: ['messages', 'conversation'],
        components: {
            MessageGroup
        },
        mounted: function () {
            this.scrollBottom();
        },
        updated: function () {
            this.scrollBottom();
        },
        methods: {
            scrollBottom: function () {
                this.$el.scrollTop = this.$el.scrollHeight;
            }
        },
        computed: {
            messageGroups: function () {
                return this.messages.reduce((messageGroups, message) => {
                    if (messageGroups.length > 0 && messageGroups[messageGroups.length - 1].user.id === message.user_id) {
                        messageGroups[messageGroups.length - 1].messages.push(message);
                    } else {
                        const user = this.conversation.users.find((user) => {
                            return message.user_id === user.id;
                        });
                        messageGroups.push({
                            user,
                            messages: [
                                message
                            ]
                        });
                    }
                    return messageGroups;
                }, []);
            }
        }
    };
</script>

<style scoped>
</style>
