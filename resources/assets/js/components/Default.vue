<template>
    <div class="wrapper">
        <slot name="default-header"></slot>
        <slot name="default-sidebar"></slot>
        <slot name="default-content"></slot>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Auth component mounted.')
        }
    }
</script>