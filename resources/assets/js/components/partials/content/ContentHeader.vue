<template>
    <section class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    <slot name="content-header-title"></slot>
                    <slot name="small-content-header-title"></slot>
                </h2>
            </div>
            <div class="col-sm-6">
                <slot name="content-header-tool"></slot>
            </div>
        </div>
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

<style>
    .content-header h2 {
        margin-top: 0;
    }
</style>