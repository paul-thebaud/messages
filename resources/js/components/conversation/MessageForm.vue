<template>
    <form class="form-inline p-2" @submit.prevent="sendMessage">
        <label class="sr-only" for="message-input">Name</label>
        <input type="text"
               autocomplete="off"
               class="flex-grow-1 form-control mr-sm-2"
               id="message-input"
               v-model="newMessage"
               @keyup="sendTypingEvent"
               placeholder="Type your message..."/>
        <button type="button" class="btn btn-unicorn mr-sm-2" @click="sendUnicorn">🦄</button>
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
            };
        },

        methods: {
            sendTypingEvent() {
                Echo.join(`App.Conversation.${this.conversationId}.chat`)
                    .whisper('typing', {
                        user_id: this.$store.getters['auth/user'].id
                    });
            },

            sendUnicorn() {
                window.giphyClient.random('gifs', {
                    tag: 'unicorn'
                })
                    .then(response => {
                        this.$emit('add-message', {
                            text: response.data.images.fixed_height_downsampled.gif_url,
                            conversation_id: this.conversationId
                        });
                    })
                    .catch(error => {
                        console.log(error);
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

                this.newMessage = '';
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .form-inline {
        background-color: white;
        border-top: 1px solid $border-color;

        .btn-unicorn {
            border: 1px solid #ced4da;
        }
    }
</style>
