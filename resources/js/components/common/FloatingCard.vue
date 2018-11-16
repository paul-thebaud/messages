<template>
    <b-container class="h-100 pt-1">
        <b-row class="align-items-center justify-content-center h-100">
            <b-col md="6">
                <b-card :title="title">
                    <slot></slot>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    export default {
        name: 'FloatingCard',
        props: ['title']
    };
</script>

<style scoped>
</style>
