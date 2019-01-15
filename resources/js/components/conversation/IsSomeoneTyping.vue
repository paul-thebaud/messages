<template>
    <li v-show="typing" class="typing text-muted px-3">
        &bull;&bull;&bull;
    </li>
</template>

<script>
    export default {
        name: 'IsSomeoneTyping',
        props: {
            conversationId: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                typing: false
            };
        },
        mounted() {
        }
    };
</script>

<style lang="scss" scoped>
    .typing {
        margin-left: 45px;
        padding: 0.25rem 0.75rem;
        background-color: white;
        border-radius: 10px 10px 10px 10px;
        margin-bottom: 0.25rem;
        display: table;;
    }
</style>
