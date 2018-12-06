<template>
    <form class="form-inline p-2">
        <label class="sr-only" for="message-input">Name</label>
        <input type="text" class="flex-grow-1 form-control mr-sm-2" id="message-input" placeholder="Type your message..."/>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</template>

<script>
    export default {
        name: 'MessageForm'
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .form-inline {
        background-color: white;
        border-top: 1px solid $border-color;
    }
</style>
