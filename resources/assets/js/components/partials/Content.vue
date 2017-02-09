<template>
    <div class="content-wrapper">
        <slot name="content-header"></slot>

        <slot name="content-body"></slot>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('component mounted.')
        }
    }
</script>