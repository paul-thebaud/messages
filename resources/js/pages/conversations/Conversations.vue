<template>
    <div class="conversations">
        <div class="conversations-list">
            <conversation-search @search="onSearch"></conversation-search>
            <div class="flex-grow-1">
                <div v-if="filteredConversations.length <= 0" class="text-muted text-center">
                    No conversation.
                </div>
                <conversation-item v-for="conversation in orderedConversations"
                                   :key="conversation.id" :conversation="conversation">
                </conversation-item>
            </div>
            <div class="conversations-list-create">
                <b-btn variant="primary" size="sm" to="/conversations/create" block>Create</b-btn>
            </div>
        </div>
        <div class="conversation-view">
            <router-view @create-conversation="createConversation"
                         @update-conversation="updateConversation"
                         @read-conversation="readConversation"
            ></router-view>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import ConversationSearch from '../../components/conversations/ConversationSearch';
    import ConversationItem from '../../components/conversations/ConversationItem';

    export default {
        name: 'Conversations',
        components: {
            ConversationSearch,
            ConversationItem
        },
        computed: {
            orderedConversations: function () {
                return this.filteredConversations.sort((a, b) => moment(a.updated_at).isAfter(b.updated_at) ? -1 : 1);
            }
        },
        data() {
            const user = this.$store.getters['auth/user'];
            return {
                userId: user.id,
                conversations: [],
                filteredConversations: []
            };
        },
        mounted() {
            this.$store.dispatch('conversation/index')
                .then(conversations => {
                    // Unregister from previous conversation listening.
                    // TODO
                    // Define new conversations.
                    this.conversations = conversations;
                    // Register for new conversations.
                    this.conversations.forEach((conversation) => {
                        Echo.private(`App.Conversation.${conversation.id}`)
                            .listen('.newMessage', ({ message }) => {
                                conversation.updated_at   = moment.utc().format("YYYY-MM-DD HH:mm:ss");
                                conversation.has_unread   = message.user_id !== this.$store.getters['auth/user'].id;
                                conversation.last_message = message.text;
                                conversation.message_count++;
                            });
                    });

                    Echo.private('App.User.' + this.userId)
                        .listen(".conversationEvent", (notification) => {
                            console.log(notification);
                            if(notification.type === "remove"){
                                this.conversations.splice(this.conversations.indexOf(notification.conversation),1);
                                if(this.$route.params.conversation_id === notification.conversation.id){
                                    this.$router.push({ name: 'Conversations' });
                                }
                            }
                            if(notification.type === "add"){
                                this.conversations.push(notification.conversation);
                            }
                        });

                    if (this.$router.currentRoute.name === 'NoConversation' && this.conversations.length > 0) {
                        this.$router.push(`/conversations/${this.conversations[0].id}`);
                    }
                    this.filteredConversations = this.conversations;
                });
        },
        methods: {
            onSearch(search) {
                const lowerSearch = (search || '').toLowerCase();
                this.filteredConversations = this.conversations.filter(function (conversation) {
                    return (conversation.name || '').toLowerCase().indexOf(lowerSearch) >= 0;
                });
            },
            createConversation(conversation) {
                this.conversations.push(conversation);
                this.$router.push(`/conversations/${conversation.id}/details`);
            },
            updateConversation(updatedConversation) {
                this.conversations.forEach((conversation) => {
                    if (conversation.id === updatedConversation.id) {
                        conversation.name = updatedConversation.name;
                        return false;
                    }
                });
            },
            readConversation(conversationId) {
                this.conversations.forEach((conversation) => {
                    if (conversation.id === conversationId) {
                        conversation.has_unread = false;
                        return false;
                    }
                });
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .conversations {
        display: flex;
        height: 100%;

        .conversations-list {
            display: flex;
            flex-direction: column;
            overflow-y: scroll;
            overflow-x: hidden;
            height: 100%;
            min-width: 250px;
            max-width: 250px;
            background-color: white;
            border-right: 1px solid $border-color;

            .conversations-list-create {
                padding: 10px;
            }
        }

        .conversation-view {
            overflow-y: hidden;
            height: 100%;
            width: 100%;
        }
    }
</style>
