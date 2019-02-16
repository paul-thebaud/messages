<template>
    <div class="flex-wrapper">
        <div class="text-center col-md-6">
            <form @submit.prevent="update">
                <b-form-group>
                    <b-form-input id="name" type="text" v-model="conversation.name"
                                  placeholder="Enter conversation's name"
                                  :state="this.error.has('name') ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback>{{ this.error.get('name') }}</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group>
                    <v-select multiple label="username" :filterable="false" :options="options" @search="onSearch" v-model="selectedUsers">
                        <template slot="no-options">
                            type to search users...
                        </template>
                        <template slot="option" slot-scope="option">
                            <div class="d-center">
                                {{ option.username }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class="selected d-center">
                                {{ option.username }}
                            </div>
                        </template>
                    </v-select>
                </b-form-group>
                <b-form-group>
                    <b-btn type="submit" variant="primary" :disabled="loading" block>Update</b-btn>
                </b-form-group>
            </form>
            <div class="row">
                <div v-for="user in conversation.users"
                     :key="user.id"
                     class="col-sm-6">
                    <b-btn variant="primary"
                           v-b-tooltip.hover
                           title="Remove this user"
                           class="mb-2"
                           block>
                        {{ user.username }}
                    </b-btn>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import VueSelect from 'vue-select';
    import api from '../../helpers/api';
    import ApiError from '../../helpers/api/ApiError';
    import diff from 'diff-arrays-of-objects';

    export default {
        name: 'ConversationDetails',
        components: {
            "v-select": VueSelect
        },
        data() {
            return {
                options: [],
                conversation: {
                    name: null,
                    users: []
                },
                selectedUsers: [],
                previousUsers: [],
                error: new ApiError(),
                loading: false
            };
        },
        mounted() {
            api.show('conversations', this.$route.params.conversation_id)
                .then(conversation => {
                    this.conversation = conversation;
                    this.selectedUsers = Array.from(this.conversation.users);
                });
        },
        methods: {
            update() {
                this.loading = true;
                const updatedUsers = diff(this.conversation.users,this.selectedUsers,'id');
                updatedUsers.added.forEach((user) => {
                    api.store(`conversations/${this.conversation.id}/users`,{user_id: user.id});
                });
                updatedUsers.removed.forEach((user) => {
                    api.destroy(`conversations/${this.conversation.id}/users`,user.id);
                });
                api.update('conversations', this.conversation.id, this.conversation)
                    .then(() => {
                        this.$emit('update-conversation', this.conversation);
                    })
                    .catch(error => {
                        this.error = error;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            onSearch(search, loading) {
                loading(true);
                this.search(loading, search, this);
            },
            search: _.debounce((loading, search, vm) => {
                    api.index('users',{search: search}).then(users => {
                    vm.options = users;
                        //res.json().then(json => (vm.options = json.items));
                    loading(false);
                });
            }, 350)
        }
    };
</script>

<style lang="scss" scoped>
    .flex-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
</style>
