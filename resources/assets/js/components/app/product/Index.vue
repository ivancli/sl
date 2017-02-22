\
<template>
    <!-- Main content -->
    <section class="content">

        <div class="box box-solid">
            <div class="box-body p-20">
                <div class="row m-b-10" v-if="hasCategories">
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
                        <add-category @added-category="loadCategories"></add-category>
                    </div>
                </div>
                <div class="row m-b-20" v-if="hasCategories">
                    <div class="col-sm-12 text-right">
                        <a href="#" class="text-muted btn-collapse-all" @click.prevent="toggleAllCategories" v-text="shouldExpandAll ? 'Expand All' : 'Collapse All'"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <single-category v-for="single_category in categories" :current-category="single_category" @reload-categories="loadCategories"></single-category>
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

    import {
            TOGGLE_ALL_CATEGORIES
    } from '../../../actions/action-types';

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
            },
            toggleAllCategories: function () {
                this.$store.dispatch(TOGGLE_ALL_CATEGORIES);
            }
        },
        computed: {
            allCollapseStatus(){
                return this.$store.getters.categoriesCollapsed;
            },
            shouldExpandAll(){
                var shouldExpand = true;
                for (var categoryCollapseStatus in this.allCollapseStatus) {
                    if (this.allCollapseStatus.hasOwnProperty(categoryCollapseStatus) && this.allCollapseStatus[categoryCollapseStatus] == false) {
                        shouldExpand = false;
                        break;
                    }
                }
                return shouldExpand;
            },
            hasCategories(){
                return this.categories.length > 0;
            }
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

    .btn-collapse-all {
        font-size: 12px;
    }
</style>