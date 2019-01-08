<template>
    <form class="form-inline p-2">
        <label class="sr-only" for="message-input">Name</label>
        <input type="text"
               class="flex-grow-1 form-control mr-sm-2"
               id="message-input"
               v-model="newMessage"
               @keyup.enter="sendMessage"
               @keyup="sendTypingEvent"
               placeholder="Type your message..."/>
        <button type="button" @click="sendMessage" class="btn btn-primary">Send</button>
    </form>
</template>

<script>
    export default {
        name: 'MessageForm',
        props: ['user','conversationId'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendTypingEvent() {
                Echo.join('chat')
                    .whisper('typing', this.user);
            },

            sendMessage() {
                this.$emit('messagesent', {
                    text: this.newMessage,
                    conversation_id: this.conversationId
                });

                this.newMessage = ''
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .form-inline {
        background-color: white;
        border-top: 1px solid $border-color;
    }
</style>
