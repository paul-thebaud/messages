<template>
    <div class="spinner-wrapper h-100 w-100">
        <div class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Spinner'
    };
</script>

<style scoped>
    .spinner-wrapper {
        position: absolute;
        left: 0;
        top: 0;
    }

    .spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        display: inline-block;
        width: 64px;
        height: 64px;
    }

    .spinner div {
        position: absolute;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #3490DC;
        animation: spinner 1.2s linear infinite;
    }

    .spinner div:nth-child(1) {
        top: 6px;
        left: 6px;
        animation-delay: 0s;
    }

    .spinner div:nth-child(2) {
        top: 6px;
        left: 26px;
        animation-delay: -0.4s;
    }

    .spinner div:nth-child(3) {
        top: 6px;
        left: 45px;
        animation-delay: -0.8s;
    }

    .spinner div:nth-child(4) {
        top: 26px;
        left: 6px;
        animation-delay: -0.4s;
    }

    .spinner div:nth-child(5) {
        top: 26px;
        left: 26px;
        animation-delay: -0.8s;
    }

    .spinner div:nth-child(6) {
        top: 26px;
        left: 45px;
        animation-delay: -1.2s;
    }

    .spinner div:nth-child(7) {
        top: 45px;
        left: 6px;
        animation-delay: -0.8s;
    }

    .spinner div:nth-child(8) {
        top: 45px;
        left: 26px;
        animation-delay: -1.2s;
    }

    .spinner div:nth-child(9) {
        top: 45px;
        left: 45px;
        animation-delay: -1.6s;
    }

    @keyframes spinner {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>
