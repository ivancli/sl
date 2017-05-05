<template>
    <div class="dotdotdot"></div>
</template>
<script>
    export default{}
</script>
<style>
    .dotdotdot {
        width: 60px;
        height: 20px;
        background: transparent url('/images/dotdotdot.png') 0 0 no-repeat;
        -webkit-background-size: 60px 480px;
        background-size: 60px 480px;
        animation: dotdotdot 1s steps(24) infinite; /* The Animation */
        -moz-animation: dotdotdot 1s steps(24) infinite;
        -webkit-animation: dotdotdot 1s steps(24) infinite;
        margin: 10px auto;
    }

    @keyframes dotdotdot {
        100% {
            background-position: 0 -480px
        }
    }
</style>