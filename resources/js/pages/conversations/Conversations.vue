<template>
    <div class="conversations">
        <div class="conversations-list">
            <conversation-search></conversation-search>
            <conversation-item v-for="conversation in orderedConversations"
                               :key="conversation.id" :conversation="conversation">
            </conversation-item>
        </div>
        <div class="conversation-view">
            <router-view></router-view>
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
                return this.conversations.sort((a, b) => moment(a.updated_at).isAfter(b.updated_at) ? -1 : 1);
            }
        },
        data() {
            const conversations = [...Array(45).keys()].map(value => {
                return {
                    id: value.toString(),
                    name: 'Some name for this conversation',
                    updated_at: new Date(+(new Date()) - Math.floor(Math.random()*100000000)).toISOString()
                };
            });
            return {
                conversations
            };
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .conversations {
        display: flex;
        height: 100%;
        .conversations-list {
            overflow-y: scroll;
            height: 100%;
            min-width: 250px;
            background-color: white;
            border-right: 1px solid $border-color;
        }
        .conversation-view {
            overflow-y: hidden;
            height: 100%;
            width: 100%;
        }
    }
</style>
