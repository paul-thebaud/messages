<template>
    <router-link :to="`/conversations/${conversation.id}`">
        <div class="conversations-list-item p-2">
            <strong class="d-block">{{ conversation.name || 'Unnamed conversation' }}</strong>
            <small class="d-block text-muted">The last message which was sended</small>
            <small class="text-muted">Updated {{ lastUpdate }}</small>
        </div>
    </router-link>
</template>

<script>
    import moment from 'moment';
    export default {
        name: 'Conversation',
        props: ['conversation'],
        computed: {
            lastUpdate() {
                return moment(this.conversation.updated_at).fromNow();
            }
        }
    };
</script>

<style scoped>
    .conversations-list-item:hover {
        background-color: #F8FAFC;
    }

    .conversations-list-item.active {
        background-color: #F8FAFC;
    }
</style>
