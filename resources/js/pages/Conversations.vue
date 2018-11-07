<template>
    <b-row class="m-0">
        <b-col md="4" lg="3" class="bg-white p-0 h-100">
            <h4 class="m-2">Conversations</h4>
            <div v-if="loading">
                <h5 class="m-2">Loading</h5>
            </div>
            <div v-else v-for="conversation in conversations">
                <conversation :conversation="conversation"></conversation>
            </div>
        </b-col>
    </b-row>
</template>

<script>
    import api from '../helpers/api';
    import Conversation from '../components/Conversation';

    export default {
        name: 'Conversations',
        components: { Conversation },
        data() {
            return {
                conversations: {},
                loading: true
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
