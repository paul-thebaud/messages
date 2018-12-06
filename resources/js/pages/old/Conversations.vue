<template>
    <b-row class="m-0 h-100">
        <b-col md="4" lg="3" class="conversations bg-white p-0 h-100">
            <conversation-list></conversation-list>
        </b-col>
        <b-col md="8" lg="9" class="p-0 h-100">
            <div class="conversation">
                <div class="messages-list" v-for="id in messages" :key="id">
                    <div class="messages-item">
                        some message
                    </div>
                    <div class="messages-item">
                        another message
                    </div>
                </div>
                <b-form class="p-3 messages-input d-flex">
                    <b-form-input type="text" size="sm" class="flex-fill" placeholder="Type a message...">
                    </b-form-input>
                    <b-button variant="primary" size="sm" class="ml-2">Send</b-button>
                </b-form>
            </div>
        </b-col>
    </b-row>
</template>

<script>
    import api from '../../helpers/api/index';
    import ConversationList from '../../components/old/ConversationList';

    export default {
        name: 'Conversations',
        components: {
            ConversationList
        },
        data() {
            return {
                conversations: {},
                loading: true,
                messages: [...Array(45).keys()],
            };
        },
        mounted() {
            this.fetch();
        },
        methods: {
            fetch() {
                this.loading = true;
                api.index('conversations')
                    .then((conversations) => {
                        this.loading       = false;
                        this.conversations = conversations;
                    });
            }
        }
    };
</script>

<style scoped>
</style>
