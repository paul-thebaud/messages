<template>
    <router-link class="conversations-item" :class="{ unread: conversation.has_unread }"
                 :to="`/conversations/${conversation.id}`">
        <div class="conversations-item__body">
            <strong class="conversations-item__name">
                {{ name }}
            </strong>
            <small class="conversations-item__last_message">
                {{ conversation.last_message || 'No message' }}
            </small>
            <small class="conversations-item__last_update">
                {{ conversation.message_count > 0 ? 'Updated' : 'Created' }} {{
                moment.utc(conversation.updated_at).fromNow() }}
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

        &.router-link-active {
            .conversations-item__body {
                background-color: $border-color;
            }
        }

        &.unread {
            .conversations-item__last_message, .conversations-item__last_update {
                font-weight: bold;
                color: $gray-800;
            }
        }
    }
</style>
