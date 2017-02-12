<template>
    <!-- Main content -->
    <section class="content">

        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">

                    </div>
                    <div class="col-md-4 text-right">
                        <a href="#" class="btn btn-purple btn-flat">
                            <i class="fa fa-bell-o"></i>
                            &nbsp;
                            SET UP ALERT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import addCategory from './AddCategory.vue';

    export default {
        mounted() {
            console.log('component mounted.')
        }
    }
</script>

<style>
    .btn-purple {
        background-color: #A892C3;
        border-color: #ccb8e4;
        color: #fff;
    }

    .btn-purple:hover, .btn-purple:active, .btn-purple.hover {
        background-color: #8b74a9;
        border-color: #9c83bf;
        color: #fff;
    }
</style>