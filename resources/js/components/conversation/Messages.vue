<template>
    <ul class="p-3"
        v-chat-scroll="{always: false, smooth: true, scrollonremoved:true}"
        v-on:scroll.native="handleScroll">
        <li class="px-3 text-center text-muted pb-1">
            <small>
                Conversation created {{ moment.utc(conversation.created_at).fromNow() }}
            </small>
        </li>
        <message-group v-for="(messageGroup, index) in messageGroups"
                       :key="index"
                       :user="messageGroup.user"
                       :messages="messageGroup.messages"
                       @image-loaded="imageLoaded"
        ></message-group>
        <li v-if="typing" class="typing text-muted px-3">
            &bull;&bull;&bull;
        </li>
    </ul>
</template>

<script>
    import moment from 'moment';
    import MessageGroup from './MessageGroup';
    import IsSomeoneTyping from '../../components/conversation/IsSomeoneTyping';

    export default {
        name: 'Messages',
        props: ['messages', 'conversation'],
        components: {
            IsSomeoneTyping,
            MessageGroup
        },
        data: function () {
            return {
                typing: false,
                moment: moment
            };
        },
        destroyed() {
            this.$el.removeEventListener('scroll', this.handleScroll);
        },
        mounted: function () {
            Echo.join(`App.Conversation.${this.conversation.id}.chat`)
                .listenForWhisper('typing', (event) => {
                    if (event.user_id === this.$store.getters['auth/user'].id) {
                        return;
                    }
                    this.typing = true;
                    setTimeout(() => {
                        this.typing = false;
                    }, 900);
                });
            this.$el.addEventListener('scroll', this.handleScroll);
        },
        updated() {
            // We are not on bottom.
            if (!this.isOnBottom()) {
                return;
            }
            this.$emit('read-message');
        },
        methods: {
            isOnTop() {
                return this.$el.scrollTop === 0;
            },
            isOnBottom() {
                return (this.$el.scrollTop + this.$el.offsetHeight) === this.$el.scrollHeight;
            },
            handleScroll() {
                if (this.isOnTop()) {
                    this.$emit('load-messages', (added) => {
                        if (added > 0) {
                            this.$el.scrollTop = this.$el.offsetHeight;
                        }
                    });
                }
                if (this.isOnBottom()) {
                    this.$emit('read-message');
                }
            },
            loadMessages() {
                this.$emit('load-messages');
            },
            imageLoaded() {
                if ((this.$el.scrollHeight - (this.$el.offsetHeight + this.$el.scrollTop)) <= 200) {
                    this.$el.scrollTop = this.$el.scrollHeight;
                }
            }
        },
        computed: {
            messageGroups: function () {
                return this.messages.reduce((messageGroups, message) => {
                    if (messageGroups.length > 0 && messageGroups[messageGroups.length - 1].user.id === message.user_id) {
                        messageGroups[messageGroups.length - 1].messages.push(message);
                    } else {
                        const user = this.conversation.users.find((user) => {
                            return message.user_id === user.id;
                        });
                        messageGroups.push({
                            user,
                            messages: [
                                message
                            ]
                        });
                    }
                    return messageGroups;
                }, []);
            }
        }
    };
</script>

<style lang="scss" scoped>
    ul.p-3 {
        list-style: none;
        margin-bottom: 0;
    }

    .typing {
        margin-left: 45px;
        padding: 0.25rem 0.75rem;
        background-color: white;
        border-radius: 10px 10px 10px 10px;
        margin-bottom: 0.25rem;
        display: table;;
    }
</style>
