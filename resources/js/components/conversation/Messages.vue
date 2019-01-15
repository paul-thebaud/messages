<template>
    <ul class="p-3"
        v-chat-scroll="{always: false, smooth: true, scrollonremoved:true}"
        v-on:scroll.native="handleScroll">
        <message-group v-for="(messageGroup, index) in messageGroups"
                       :key="index"
                       :user="messageGroup.user"
                       :messages="messageGroup.messages"
        ></message-group>
        <li v-if="typing" class="typing text-muted px-3">
            &bull;&bull;&bull;
        </li>
    </ul>
</template>

<script>
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
                typing: false
            };
        },
        destroyed () {
            this.$el.removeEventListener('scroll', this.handleScroll);
        },
        mounted: function () {
            Echo.join(`App.Conversation.${this.conversation.id}.chat`)
                .listenForWhisper('typing', (event) => {
                    //if (event.user_id === this.$store.getters['auth/user'].id) {
                    //    return;
                    //}
                    this.typing = true;

                    // Remove is typing indicator after 0.9s.
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
            isOnBottom() {
                return (this.$el.scrollTop + this.$el.offsetHeight) === this.$el.scrollHeight;
            },
            handleScroll() {
                if (this.isOnBottom()) {
                    this.$emit('read-message');
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
