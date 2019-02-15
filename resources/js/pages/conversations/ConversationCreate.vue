<template>
    <div class="flex-wrapper">
        <div class="text-center col-md-6">
            <form @submit.prevent="create">
                <b-form-group>
                    <b-form-input id="name" type="text" v-model="conversation.name"
                                  placeholder="Enter conversation's name"
                                  :state="this.error.has('name') ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback>{{ this.error.get('name') }}</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group>
                    <b-btn type="submit" variant="primary" :disabled="loading" block>Create</b-btn>
                </b-form-group>
            </form>
        </div>
    </div>
</template>

<script>
    import VueSelect from 'vue-select';
    import api from '../../helpers/api';
    import ApiError from '../../helpers/api/ApiError';

    export default {
        name: 'ConversationCreate',
        components: {
            VueSelect
        },
        data() {
            return {
                conversation: {
                    type: 'group',
                    name: null
                },
                error: new ApiError(),
                loading: false
            };
        },
        methods: {
            create() {
                this.loading = true;
                api.store('conversations', this.conversation)
                    .then(conversation => {
                        this.$emit('create-conversation', conversation);
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
