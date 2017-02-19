\
<template>
    <!-- Main content -->
    <section class="content">

        <div class="box box-solid">
            <div class="box-body p-20">
                User Index page here
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        mounted() {
            console.info('Index component is mounted');
            this.loadCategories();
        }
    }
</script>

<style>
</style>