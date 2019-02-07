<template>
    <div v-html="htmlContent"></div>
</template>

<script>
    import linkify from 'linkifyjs/string';

    export default {
        name: 'Message',
        props: {
            message: {
                required: true,
                type: Object
            }
        },
        computed: {
            htmlContent() {
                return linkify(this.message.text);
            }
        }
    };
</script>

<style scoped>

</style>
