<template>
    <!-- Main content -->
    <section class="content">

        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-10">
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
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <add-category @addedCategory="loadCategories"></add-category>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <!--Collapse All-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <single-category v-for="single_category in categories" :current-category="single_category"></single-category>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12" v-for="category in categories">
                        <div v-for="product in category.products">
                            {{product.id}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import addCategory from './AddCategory.vue';
    import singleCategory from './SingleCategory.vue';

    export default {
        components: {
            addCategory,
            singleCategory
        },
        data() {
            return {
                categories: []
            }
        },
        mounted() {
            console.info('Index component is mounted');
            this.loadCategories();
        },
        methods: {
            loadCategories: function () {
                axios.get('/category').then(response=> {
                    if (response.data.status == true) {
                        this.categories = response.data.categories;
                    }
                }).catch(error=> {
                    console.info(error.response);
                })
            }
        },
        computed: {}
//        store
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