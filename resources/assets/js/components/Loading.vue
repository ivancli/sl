<template>
    <div class="loading">
        <div class="loading-backdrop"></div>
        <div class="loading-core"></div>
    </div>
</template>

<script>
    export default {
        mounted() {

        }
    }
</script>

<style>
    .loading-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: #000;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        filter: alpha(opacity=50);
        -moz-opacity: 0.5;
        -khtml-opacity: 0.5;
        opacity: 0.5;
    }

    .loading .loading-core {
        position: fixed;
        top: 50%;
        left: 50%;
        margin-left: -50px;
        margin-top: -50px;
        width: 100px;
        height: 100px;
        background-image: url('../../images/spinner.png');
        background-size: 100px 3600px;
        -webkit-animation: loading-v 1s steps(36) infinite;
    }

    @keyframes loading-v {
        0% {
            background-position-y: 0;
        }
        100% {
            background-position-y: -3600px;
        }
    }
</style>