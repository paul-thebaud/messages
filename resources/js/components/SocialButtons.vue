<template>
    <b-button-group class="d-flex">
        <b-button class="btn-google w-100" @click="redirect('google')">Via Google</b-button>
        <b-button class="btn-facebook w-100" @click="redirect('facebook')">Via Facebook</b-button>
    </b-button-group>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'SocialButtons',
        methods: {
            redirect(driver) {
                axios.get('/api/oauth/redirect', { params: { driver } })
                    .then((response) => {
                        window.location.href = response.data.url;
                    });
            }
        }
    };
</script>

<style scoped>
    .btn-facebook {
        background-color: #3B5998;
    }

    .btn-facebook:active, .btn-facebook:hover {
        background-color: #2E4C8B;
    }

    .btn-google {
        background-color: #DB4437;
    }

    .btn-google:active, .btn-google:hover {
        background-color: #CE372A;
    }
</style>
