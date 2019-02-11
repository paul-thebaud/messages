<template>
    <div class="conversations-search">
        <b-form-input class="conversations-search__input"
                      type="text" size="sm" @input="search"
                      placeholder="Search in Messages...">
        </b-form-input>
    </div>
</template>

<script>
    import { debounce } from '../../helpers';

    export default {
        name: 'ConversationSearch',
        methods: {
            search: debounce(function (search) {
                this.$emit('search', search);
            }, 200)
        }
    };
</script>

<style lang="scss" scoped>
    .conversations-search {
        padding: 10px;

        &__input {
        }
    }
</style>
