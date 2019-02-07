<template>
    <li class="message-group" :class="{ 'is-author': isAuthor }">
        <img v-if="!isAuthor" :src="user.gravatar" class="gravatar"/>
        <div class="message-group-wrapper">
            <div class="author">
                <small class="text-muted">{{ user.username }}</small>
            </div>
            <div class="messages">
                <message v-for="message in messages" :key="message.id" :message="message" class="message"/>
            </div>
            <div class="date">
                <small class="text-muted">{{ lastMessageAt }}</small>
            </div>
        </div>
    </li>
</template>

<script>
    import moment from 'moment';
    import Message from './Message';

    export default {
        name: 'MessageGroup',
        props: {
            user: {
                required: true,
                type: Object
            },
            messages: {
                required: true,
                type: Array
            }
        },
        components: {
            Message
        },
        computed: {
            isAuthor: function () {
                return this.user.id === this.$store.getters['auth/user'].id;
            },
            lastMessageAt: function () {
                return moment.utc(this.messages[this.messages.length - 1].created_at).fromNow();
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../scss/variables';

    .message-group {
        margin-top: 0.75rem;

        .message-group-wrapper {
            margin-left: 45px;
        }

        .author, .date {
            margin-left: 0.50rem;
        }

        .messages {
            .message {
                background-color: white;
                padding: 0.25rem 0.75rem;
                margin-bottom: 0.25rem;
                display: table;
                border-radius: 0 10px 10px 0;

                &:first-child {
                    border-radius: 10px 10px 10px 0;
                }

                &:last-child {
                    border-radius: 0 10px 10px 10px;
                    margin-bottom: 0;
                }

                &:only-child {
                    border-radius: 10px;
                }
            }
        }

        &.is-author {
            text-align: right;

            .author, .date {
                margin-right: 0.50rem;
            }

            .messages {
                .message {
                    margin-left: auto;
                    margin-right: 0;
                    border-radius: 10px 0 0 10px;
                    background-color: $blue;
                    color: white;

                    a.linkified {
                        color: white !important;
                    }

                    &:first-child {
                        border-radius: 10px 10px 0 10px;
                    }

                    &:last-child {
                        border-radius: 10px 0 10px 10px;
                    }

                    &:only-child {
                        border-radius: 10px;
                    }
                }
            }
        }

        &:not(.is-author) {
            .gravatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                float: left;
                margin-top: 5px;
            }

            .message-group-wrapper {
                margin-left: 45px;
            }
        }
    }
</style>
