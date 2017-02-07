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
        width: 400px;
        height: 300px;
        background-image: url('../../images/spinner.png');
        -webkit-animation: loading-h 1s steps(9) infinite, loading-v 1s steps(21) infinite;
    }

    @keyframes loading-h {
        0% {
            background-position-x: 0;
        }
        100% {
            background-position-x: -3600px;
        }
    }

    @keyframes loading-v {
        0% {
            background-position-y: 0;
        }
        100% {
            background-position-y: -6300px;
        }
    }
</style>