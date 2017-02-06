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
</style>