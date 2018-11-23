<template>
    <form class="message-form">
        <input type="text" class="form-control">
        <button class="btn btn-primary">Send</button>
    </form>
</template>

<script>
    export default {
        name: 'MessageForm'
    };
</script>

<style lang="scss" scoped>
    .message-form {
        display: block;
    }
</style>
