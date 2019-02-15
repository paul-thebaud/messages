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
    import VueSelect from 'vue-select';
    import api from '../../helpers/api';
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'ConversationDetails',
        components: {
            VueSelect
        },
        data() {
            return {
                conversation: {
                    name: null
                },
                error: new ApiError(),
                loading: false
            };
        },
        mounted() {
            api.show('conversations', this.$route.params.conversation_id)
                .then(conversation => this.conversation = conversation);
        },
        methods: {
            update() {
                this.loading = true;
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
            }
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
