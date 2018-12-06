<template>
    <div class="p-3">
        <message-group v-for="(message, index) in messages"
                       :key="index"
                       :user="message.user"
                       :messages="message.messages"
        ></message-group>
    </div>
</template>

<script>
    import MessageGroup from './MessageGroup';

    export default {
        name: 'Messages',
        props: ['messages'],
        components: {
            MessageGroup
        }
    };
</script>

<style scoped>
</style>
