<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="add-item-block add-category-container">
                <div class="add-item-label" v-show="!addingCategory" @click="goingToAddCategory">
                    <i class="fa fa-plus label-icon"></i>&nbsp;&nbsp;&nbsp;
                    <span class="add-item-text">ADD CATEGORY</span>
                </div>
                <div class="add-item-controls" v-show="addingCategory">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" autocomplete="off" class="form-control txt-item" placeholder="Enter a category name here" v-model="newCategoryName" ref="txt_new_category" tabindex="-1">
                            <div class="buttons">
                                <button class="btn btn-primary btn-flat" @click="addCategory">
                                    <span class="hidden-sm hidden-xs">
                                        ADD CATEGORY
                                    </span>
                                    <span class="visible-sm visible-xs">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </button>
                                &nbsp;&nbsp;
                                <button class="btn btn-default btn-flat" @click="cancelAddCategory">
                                    <span class="hidden-sm hidden-xs">
                                        CANCEL
                                    </span>
                                    <span class="visible-sm visible-xs">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default {
        components: {
            errorModal
        },
        mounted() {
            console.info('AddCategory component mounted.')
        },
        data: ()=> {
            return {
                addingCategory: false, //determine visibility of panel
                newCategoryName: '', //category name input value
                isAddingCategory: false, //promise of form submission,
                errors: {},
            }
        },
        methods: {
            goingToAddCategory: function () {
                this.addingCategory = true;
                setTimeout(()=> {
                    this.$refs['txt_new_category'].focus();
                }, 10)
            },
            cancelAddCategory: function () {
                this.addingCategory = false;
                this.$refs['txt_new_category'].blur();
            },
            addCategory: function () {
                this.isAddingCategory = true;
                this.errors = {};
                axios.post('/category', this.addCategoryData).then(response=> {
                    this.isAddingCategory = false;
                    if (response.data.status == true) {
                        this.addingCategory = false;
                    }
                    this.newCategoryName = '';
                    this.$emit('addedCategory');
                }).catch(error=> {
                    this.isAddingCategory = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            }
        },
        computed: {
            addCategoryData: function () {
                return {
                    name: this.newCategoryName
                };
            }
        }
    }
</script>

<style>
    .add-item-block {
        border: 2px dashed lightgrey;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #777;
        font-weight: bold;
        height: 65px;
        cursor: pointer;
    }

    .add-item-block .add-item-label, .add-item-block .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .add-item-block i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .add-item-block span.add-item-text {
        font-size: 14px;
        vertical-align: middle;
    }

    .add-item-block .add-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    .add-item-block .add-item-controls .buttons {
        position: absolute;
        right: 20px;
        top: 0;
    }

    .add-item-block .add-item-controls input {
        font-size: 18px;
        padding-right: 250px;
    }

    @media (max-width: 991px) {
        .add-item-block .add-item-controls input {
            padding-right: 100px;
        }
    }

    .add-item-block .txt-item {
        border: none;
    }

    @media (min-width: 768px) {
        .add-item-block .add-item-controls {
            padding-left: 50px;
        }
    }
</style>