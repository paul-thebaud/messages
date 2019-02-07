<template>
    <div v-if="conversation !== null" class="conversation">
        <div class="conversation__header">
            <div class="title">
                {{ name }}
            </div>
            <div class="details">
                <b-btn variant="primary" size="sm" :to="`/conversations/${conversation.id}/details`">
                    Details
                </b-btn>
            </div>
        </div>
        <messages class="conversation__messages"
                  :messages="messages"
                  :conversation="conversation"
                  @read-message="readMessage"
                  @load-messages="loadMessages"
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
        created() {
        },
        data() {
            return {
                conversation: null,
                messages: [],
                messagesComponent: null
            };
        },
        methods: {
            initComponent(conversationId) {
                this.conversation = null;
                this.messages     = [];
                api.show('conversations', conversationId)
                    .then((conversation) => {
                        this.conversation = conversation;
                        this.loadMessages();
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
            },
            loadMessages(callback = null) {
                api
                    .index(`conversations/${this.conversation.id}/messages`, {
                        skip: this.messages.length
                    })
                    .then((messages) => {
                        messages.forEach((message) => {
                            this.messages.unshift(message);
                        });
                        if (callback) {
                            callback(messages.length);
                        }
                    });
            }
        },
        computed: {
            name() {
                let name = this.conversation.name;
                if (!name) {
                    name = this.conversation.users.map(function (user) {
                        return user.username;
                    }).join(', ');
                }
                const dots = name.length > 30 ? '...' : '';
                return `${name.substr(0, 30)}${dots}`;
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

        &__header {
            display: flex;
            align-items: center;
            height: 50px;
            padding: 5px 15px;
            background-color: white;
            border-bottom: 1px solid $border-color;

            .title {
                font-size: 1.2em;
                font-weight: bold;
                line-height: 40px;
                flex-grow: 1;
            }

            .details {
                vertical-align: middle;
            }
        }

        &__messages {
            height: 100%;
            overflow-y: scroll;
        }
    }
</style>
