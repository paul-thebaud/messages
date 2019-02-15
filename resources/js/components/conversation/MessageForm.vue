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
        <div :class="{ displayed: displayEmojiPicker }"
             class="emoji-picker-wrapper"
        >
            <picker color="#3490DC"
                    @select="addEmoji"
                    v-click-outside="closeEmojiPicker"
                    title="Emoji power"
                    :native="true"
                    emoji="unicorn_face"
            ></picker>
        </div>
        <button type="button"
                class="btn btn-emoji mr-sm-2"
                @click="sendUnicorn"
                v-b-tooltip.hover
                title="🦄 Random Unicorn GIF 🦄"
        >
            <emoticon-kiss-icon fillColor="#6c757d"/>
        </button>
        <button type="button"
                class="btn btn-emoji mr-sm-2"
                @click="openEmojiPicker"
                v-b-tooltip.hover
                title="Emoji"
        >
            <emoticon-icon fillColor="#6c757d"/>
        </button>
        <button type="submit"
                class="btn btn-primary"
                :disabled="newMessage === ''"
        >
            <send-icon/>
        </button>
    </form>
</template>

<script>
    import { Picker } from 'emoji-mart-vue';
    import EmoticonIcon from 'vue-material-design-icons/Emoticon';
    import EmoticonKissIcon from 'vue-material-design-icons/EmoticonKiss';
    import SendIcon from 'vue-material-design-icons/Send';

    export default {
        name: 'MessageForm',
        props: ['conversationId'],
        components: {
            EmoticonIcon,
            EmoticonKissIcon,
            SendIcon,
            Picker,
        },
        data() {
            return {
                displayEmojiPicker: false,
                newMessage: ''
            };
        },
        methods: {
            openEmojiPicker() {
                setTimeout(() => {
                    this.displayEmojiPicker = true;
                }, 100);
            },

            closeEmojiPicker() {
                if (this.displayEmojiPicker) {
                    this.displayEmojiPicker = false;
                }
            },

            addEmoji(emoji) {
                this.newMessage += emoji.native;
            },

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

        .btn-emoji {
            border: 1px solid #CED4DA;
        }

        .emoji-picker-wrapper {
            display: none;
            position: absolute;
            right: 5px;
            bottom: 60px;

            .emoji-mart {
                font-family: "Nunito", sans-serif !important;
            }

            &.displayed {
                display: block;
            }
        }
    }
</style>
