<template>
    <div class="app-title">
        <img src="/ms-icon-310x310.png" alt="Messages App Logo">
        Messages
    </div>
</template>

<script>
    export default {
        name: 'AppTitle'
    };
</script>

<style lang="scss" scoped>
    @import '../../../scss/variables';

    .app-title {
        margin-bottom: 10px;
        text-align: center;
        line-height: 38px;
        vertical-align: middle;
        color: $text-muted;
        font-weight: bold;
        font-size: 1.2rem;
        img {
            max-height: 38px;
        }
    }
</style>
