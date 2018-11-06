<template>
    <router-link :to="`/conversations/${this.conversation.id}`">
        <div class="p-2 conversation-link">
            <h5>
                {{ this.conversation.name }}
            </h5>
            <small>
                {{ this.lastUpdate }}
            </small>
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
    .conversation-link {
        border-top: 1px solid #E9ECEF;
    }
</style>
