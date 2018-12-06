<template>
    <div class="p-3">
        <message-group :user="this.$store.getters['auth/user']"
                       :messages="[
                       {id: 123, text: 'Some content', created_at: '2018-12-06 12:00:12'},
                       {id: 123, text: 'Some other content', created_at: '2018-12-06 12:00:12'}
                       ]"
        ></message-group>
    </div>
</template>

<script>
    import MessageGroup from './MessageGroup';

    export default {
        name: 'Messages',
        props: ['conversation'],
        components: {
            MessageGroup
        }
    };
</script>

<style scoped>
</style>
