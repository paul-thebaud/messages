<template>
    <div class="flex-wrapper">
        <div class="text-center">
            <h1>
                No selected conversation.
            </h1>
            <b-btn variant="primary" size="lg">Create a conversation</b-btn>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'NoSelectedConversation'
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
