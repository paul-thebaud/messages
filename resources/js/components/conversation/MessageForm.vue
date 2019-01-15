<template>
    <form class="form-inline p-2" @submit.prevent="sendMessage">
        <label class="sr-only" for="message-input">Name</label>
        <input type="text"
               class="flex-grow-1 form-control mr-sm-2"
               id="message-input"
               v-model="newMessage"
               @keyup="sendTypingEvent"
               placeholder="Type your message..."/>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</template>

<script>
    export default {
        name: 'MessageForm',
        props: ['conversationId'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendTypingEvent() {
                Echo.join(`App.Conversation.${this.conversationId}.chat`)
                    .whisper('typing', {
                        user_id: this.$store.getters['auth/user'].id,
                    });
            },

            sendMessage() {
                if (this.newMessage === '') {
                    return;
                }
                this.$emit('add-message', {
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
