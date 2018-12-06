<template>
    <div class="conversations-search">
        <b-form-input class="conversations-search__input"
                      type="text" size="sm" @input="search"
                      placeholder="Search in Messages...">
        </b-form-input>
    </div>
</template>

<script>
    export default {
        name: 'ConversationSearch',
        methods: {
            search(event) {
                this.$emit('search', event.target.value);
            }
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
