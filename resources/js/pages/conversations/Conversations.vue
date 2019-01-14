<template>
    <div class="conversations">
        <div class="conversations-list">
            <conversation-search v-on:search="onSearch"></conversation-search>
            <conversation-item v-for="conversation in orderedConversations"
                               :key="conversation.id" :conversation="conversation">
            </conversation-item>
            <div v-if="conversations.length <= 0" class="text-muted text-center">
                No conversation.
            </div>
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
            return {
                conversations: []
            };
        },
        mounted() {
            this.onSearch();
        },
        methods: {
            onSearch(search) {
                this.$store.dispatch('conversation/index', search)
                    .then(conversations => this.conversations = conversations);
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
