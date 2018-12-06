<template>
    <div class="conversation">
        <div class="conversation__title">
            {{ conversation.name || 'Loading...' }}
        </div>
        <messages class="conversation__messages" :messages="messages"></messages>
        <message-form class="conversation__message-form"></message-form>
    </div>
</template>

<script>
    import Messages from '../../components/conversation/Messages';
    import MessageForm from '../../components/conversation/MessageForm';

    export default {
        name: 'Conversation',
        components: {
            Messages,
            MessageForm
        },
        data() {
            return {
                conversation: {},
                messages: []
            };
        },
        mounted() {
            this.$store.dispatch('conversation/show', this.$route.params.conversation_id)
                .then(({ conversation, messages }) => {
                    this.conversation = conversation;
                    this.messages     = messages.reduce((newMessages, message) => {
                        if (newMessages.length > 0 && newMessages[newMessages.length - 1].user.id === message.user_id) {
                            newMessages[newMessages.length - 1].messages.push(message);
                        } else {
                            newMessages.push({
                                user: message.user,
                                messages: [
                                    message
                                ]
                            });
                        }
                        return newMessages;
                    }, []);
                    const container = this.$el.querySelector(".conversation__messages");
                    console.log(container.scrollHeight);
                    container.scrollTop = container.scrollHeight;
                });
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .conversation {
        display: flex;
        flex-direction: column;
        height: 100%;
        &__title {
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            height: 50px;
            line-height: 40px;
            padding: 5px;
            background-color: white;
            border-bottom: 1px solid $border-color;
        }
        &__messages {
            height: 100%;
            overflow-y: scroll;
        }
    }
</style>
