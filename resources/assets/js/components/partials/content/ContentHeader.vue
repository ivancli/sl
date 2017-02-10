<template>
    <section class="content-header">
        <h1>
            <slot name="content-header-title"></slot>
            <slot name="small-content-header-title"></slot>
        </h1>
        <slot name="content-header-breadcrumb"></slot>
    </section>
</template>

<script>
    export default {
        mounted() {
            console.log('content-header component mounted.')
        }
    }
</script>