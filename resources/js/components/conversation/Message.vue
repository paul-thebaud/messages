<template>
    <div v-if="!isGif" class="message" v-html="htmlContent"></div>
    <div v-else class="message gif" :class="{ loaded: imageIsLoaded }">
        <img :src="this.message.text" alt="Gif from Giphy" @load="imageLoaded"/>
    </div>
</template>

<script>
    import linkify from 'linkifyjs/string';

    const GIPHY_URL_PATTERN = new RegExp('^https:\\/\\/media[0-9]+.giphy\\.com\\/media\\/[a-zA-Z0-9]+\\/200_d\\.gif$');

    export default {
        name: 'Message',
        props: {
            message: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                imageIsLoaded: false
            };
        },
        methods: {
            imageLoaded() {
                this.imageIsLoaded = true;
                this.$emit('image-loaded');
            }
        },
        computed: {
            htmlContent() {
                return linkify(this.message.text);
            },
            isGif() {
                return GIPHY_URL_PATTERN.test(this.message.text);
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../scss/variables';

    .message {
        background-color: white;
        padding: 0.25rem 0.75rem;
        margin-bottom: 0.25rem;
        display: table;
        border-radius: 0 10px 10px 0;
        word-break: break-all;

        img {
            max-width: 100%;
            border-radius: 0 10px 10px 0;
        }

        &:first-child, &:first-child img {
            border-radius: 10px 10px 10px 0;
        }

        &:last-child, &:last-child img {
            border-radius: 0 10px 10px 10px;
            margin-bottom: 0;
        }

        &:only-child, &:only-child img {
            border-radius: 10px;
        }

        &.gif {
            padding: 0;
            height: 200px;
            &.loaded {
                height: auto;
            }
        }
    }

    .is-author {
        .message {
            margin-left: auto;
            margin-right: 0;
            border-radius: 10px 0 0 10px;
            background-color: $blue;
            color: white;

            img {
                border-radius: 10px 0 0 10px;
            }

            a.linkified {
                color: white !important;
            }

            &:first-child, &:first-child img {
                border-radius: 10px 10px 0 10px;
            }

            &:last-child, &:last-child img {
                border-radius: 10px 0 10px 10px;
            }

            &:only-child, &:only-child img {
                border-radius: 10px;
            }
        }
    }
</style>
