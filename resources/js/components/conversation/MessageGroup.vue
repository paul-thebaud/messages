<template>
    <div class="message-group" :class="{ 'is-author': isAuthor }">
        <img v-if="!isAuthor" :src="user.gravatar" class="gravatar"/>
        <div class="message-group-wrapper">
            <div class="author">
                <small class="text-muted">{{ user.username }}</small>
            </div>
            <div class="messages">
                <div v-for="message in messages" class="message">
                    {{ message.text }}
                </div>
            </div>
            <div class="date">
                <small class="text-muted">{{ createdAt }}</small>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

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
        data() {
            return {
                isAuthor: this.user.id === this.$store.getters['auth/user'].id,
                createdAt: moment(this.messages[this.messages.length - 1]).fromNow()
            };
        }
    };
</script>

<style lang="scss" scoped>
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
