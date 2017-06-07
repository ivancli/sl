<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="add-item-block add-category-container">
                <div class="add-item-label" v-show="!addingCategory" @click.prevent="goingToAddCategory">
                    <i class="fa fa-plus label-icon"></i>&nbsp;&nbsp;&nbsp;
                    <span class="add-item-text">ADD CATEGORY</span>
                </div>
                <div class="add-item-controls" v-show="addingCategory">
                    <div class="row">
                        <div class="col-sm-12">
                            <form>
                                <input type="text" autocomplete="off" class="form-control txt-item input-sm" placeholder="Enter a category name" v-model="newCategoryName" ref="txt_new_category"
                                       tabindex="-1">
                                <div class="buttons">
                                    <button class="btn btn-primary btn-flat btn-sm" @click.prevent="addCategory">
                                        <span class="hidden-sm hidden-xs">
                                            CONFIRM
                                        </span>
                                        <span class="visible-sm visible-xs">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-default btn-flat btn-sm" @click.prevent="cancelAddCategory">
                                        <span class="hidden-sm hidden-xs">
                                            CANCEL
                                        </span>
                                        <span class="visible-sm visible-xs">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </button>
                                </div>
                            </form>
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
        data() {
            return {
                addingCategory: false, //determine visibility of panel
                newCategoryName: '', //category name input value
                isAddingCategory: false, //promise of form submission,
                errors: {},
            }
        },
        methods: {
            goingToAddCategory () {
                this.addingCategory = true;
                setTimeout(() => {
                    this.$refs['txt_new_category'].focus();
                }, 10)
            },
            cancelAddCategory () {
                this.addingCategory = false;
                this.clearNewCategoryname();
                this.$refs['txt_new_category'].blur();
            },
            addCategory () {
                this.$refs['txt_new_category'].blur();
                this.isAddingCategory = true;
                this.errors = {};
                axios.post('/category', this.addCategoryData).then(response => {
                    this.isAddingCategory = false;
                    if (response.data.status === true) {
                        this.addingCategory = false;
                        this.clearNewCategoryname();
                        this.$emit('added-category', response.data.category);
                    }
                }).catch(error => {
                    this.isAddingCategory = false;
                    if (error.response && error.response.status === 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors () {
                this.errors = {};
            },
            clearNewCategoryname () {
                this.newCategoryName = '';
            },
        },
        computed: {
            addCategoryData() {
                return {
                    category_name: this.newCategoryName
                };
            },
        }
    }
</script>

<style>
    .add-category-container {
        border: 2px dashed lightgrey;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        color: #777;
        font-weight: bold;
        cursor: pointer;
    }

    .add-category-container {
        width: 415px;
        max-width: 100%;
        height: 60px;
    }

    .add-category-container .add-item-label, .add-category-container .upgrade-for-add-item-controls {
        padding: 19px 19px 19px 50px;
    }

    .add-category-container i.label-icon {
        font-size: 25px;
        vertical-align: middle;
    }

    .add-category-container span.add-item-text {
        font-size: 14px;
        vertical-align: middle;
    }

    .add-category-container .add-item-controls {
        background-color: #fff;
        padding: 13px;
    }

    .add-category-container .add-item-controls .buttons {
        position: absolute;
        right: 20px;
        top: 0;
    }

    .add-category-container .add-item-controls input {
        font-size: 18px;
        padding-right: 175px;
    }

    @media (max-width: 991px) {
        .add-category-container .add-item-controls input {
            padding-right: 100px;
        }
    }

    .add-category-container .txt-item {
        border: none;
    }

    @media (min-width: 768px) {
        .add-category-container .add-item-controls {
            padding-left: 15px;
        }
    }
</style>