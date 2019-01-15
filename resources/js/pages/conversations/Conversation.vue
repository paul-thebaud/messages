<template>
    <div v-if="conversation !== null" class="conversation">
        <div class="conversation__title">
            {{ conversation.name || 'Unnamed conversation' }}
        </div>
        <messages class="conversation__messages"
                  :messages="messages"
                  :conversation="conversation"
                  @read-message="readMessage"
        ></messages>
        <message-form class="conversation__message-form"
                      :conversationId="conversation.id"
                      @add-message='addMessage'
        ></message-form>
    </div>
</template>

<script>
    import api from '../../helpers/api';
    import Messages from '../../components/conversation/Messages';
    import MessageForm from '../../components/conversation/MessageForm';

    export default {
        name: 'Conversation',
        components: {
            Messages,
            MessageForm
        },
        beforeRouteUpdate(to, from, next) {
            this.initComponent(to.params.conversation_id);
            next();
        },
        mounted() {
            this.initComponent(this.$route.params.conversation_id);
        },
        data() {
            return {
                conversation: null,
                messages: []
            };
        },
        methods: {
            initComponent(conversationId) {
                this.$store.dispatch('conversation/show', conversationId)
                    .then(({ conversation, messages }) => {
                        this.conversation = conversation;
                        this.messages     = messages;
                        Echo.private(`App.Conversation.${conversation.id}`)
                            .listen('.newMessage', ({ message }) => {
                                if (message.user_id === this.$store.getters['auth/user'].id) {
                                    return;
                                }
                                this.messages.push(message);
                            });
                    });
            },
            addMessage(message) {
                api.store(`conversations/${this.conversation.id}/messages`, message)
                    .then((message) => {
                        this.messages.push(message);
                    });
            },
            readMessage() {
                const userId      = this.$store.getters['auth/user'].id;
                const lastMessage = this.messages[this.messages.length - 1] || null;
                // No last message or user is author.
                if (!lastMessage || lastMessage.user_id === userId) {
                    return;
                }
                // Last message already marked as read.
                if (lastMessage.user_ids.includes(userId)) {
                    return;
                }
                api.store(`conversations/${lastMessage.conversation_id}/messages/${lastMessage.id}/users`)
                    .then(() => {
                        lastMessage.user_ids.push(userId);
                        this.$emit('read-conversation', lastMessage.conversation_id);
                    });
            }
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
