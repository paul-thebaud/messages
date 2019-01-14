<template>
    <router-link class="conversations-item" :class="{ active: isActive }" :to="`/conversations/${conversation.id}`">
        <div class="conversations-item__body">
            <strong class="conversations-item__name">
                {{ conversation.name || 'Unnamed conversation' }}
            </strong>
            <small class="conversations-item__last_message">
                {{ conversation.last_message || 'No message' }}
            </small>
            <small class="conversations-item__last_update">
                Updated {{ moment(conversation.updated_at).fromNow() }}
            </small>
        </div>
    </router-link>
</template>

<script>
    import moment from 'moment';

    export default {
        name: 'ConversationItem',
        props: ['conversation'],
        data() {
            return { moment };
        },
        computed: {
            isActive: function () {
                return this.$route.params.conversation_id === this.conversation.id;
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .conversations-item {
        text-decoration: none;
        &__body {
            transition: 250ms ease;
            color: $gray-900;
            padding: 5px 10px;
            &:hover {
                background-color: $border-color;
            }
        }
        &__name, &__last_message, &__last_update {
            display: block;
        }
        &__last_message, &__last_update {
            color: $text-muted;
        }
        &.active {
            .conversations-item__body {
                background-color: $border-color;
            }
        }
    }
</style>
