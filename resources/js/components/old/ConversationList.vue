<template>
    <div class="conversations h-100 mh-100">
        <form class="p-3 conversations-search">
            <b-form-input type="text" size="sm"
                          placeholder="Search in Messages..."
                          @input="search">
            </b-form-input>
        </form>
        <div v-if="form.searching" class="text-center">
            <small class="text-muted">
                Loading ...
            </small>
        </div>
        <div v-else class="conversations-list">
            <conversation-item v-for="conversation in conversations"
                               :key="conversation.id" :conversation="conversation">
            </conversation-item>
            <div v-if="conversations.length === 0" class="text-center">
                <small class="text-muted">
                    No conversation found.
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../../helpers/api/index';
    import ConversationItem from './ConversationItem';
    import debounce from 'debounce';

    export default {
        name: 'ConversationList',
        data() {
            return {
                form: {
                    search: '',
                    searching: false
                },
                conversations: []
            };
        },
        mounted() {
            this.fetch('');
        },
        components: {
            ConversationItem
        },
        methods: {
            search(search) {
                this.form.searching = true;
                this.fetch(search);
            },
            fetch: debounce(function (search) {
                this.form.search = search;
                const params     = this.form.search !== '' ? { search: this.form.search } : {};
                api.index('conversations', params)
                    .then((conversations) => {
                        this.form.searching = false;
                        this.conversations  = conversations;
                    });
            }, 500)
        }
    };
</script>

<style scoped>
    .conversations {
        overflow-y: scroll;
        border-right: 1px solid rgba(0, 0, 0, 0.075);
    }

    .conversations-list a {
        text-decoration: none;
        color: inherit;
    }
</style>
