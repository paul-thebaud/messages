<template>
    <div class="conversation">
        <div class="conversation__title">
            {{ conversation.name }}
        </div>
        <messages class="conversation__messages"></messages>
        <message-form class="conversation__message-form"></message-form>
    </div>
</template>

<script>
    import Messages from '../../components/conversation/Messages';
    import MessageForm from '../../components/conversation/MessageForm';

    export default {
        name: 'Conversation',
        components: {
            Messages,
            MessageForm
        },
        data() {
            return {
                conversation: {
                    id: this.$route.params.conversation_id,
                    name: 'Some name for this conversation'
                }
            };
        }
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .conversation {
        display: flex;
        flex-direction: column;
        height: 100%;
        &__title {
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            height: 50px;
            line-height: 40px;
            padding: 5px;
            background-color: white;
            border-bottom: 1px solid $border-color;
        }
        &__messages {
            height: 100%;
        }
    }
</style>
