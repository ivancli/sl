<template>
    <div class="edit-category-wrapper">
        <span class="btn-edit btn-edit-category" @click.prevent="goingToEditCategoryName" v-show="!editingCategoryName">
            <span class="hidden-xs hidden-sm">Edit &nbsp;</span>
            <i class="fa fa-pencil-square-o"></i>
        </span>

        <div class="input-group sl-input-group" v-show="editingCategoryName">
            <input type="text" placeholder="Category Name" autocomplete="off" v-model="newCategoryName" class="form-control sl-form-control category-name" ref="txt_edit_category" tabindex="-1">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-flat" @click.prevent="editCategory">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn btn-default btn-flat" @click.prevent="cancelEditCategoryName">
                    <i class="fa fa-times"></i>
                </button>
            </span>
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default{
        components: {
            errorModal
        },
        props: [
            'editing-category'
        ],
        data() {
            return {
                editingCategoryName: false, //determine visibility of panel
                newCategoryName: '', //category name input value
                isEditingCategory: false, //promise of form submission,
                errors: {},
            }
        },
        mounted(){
            this.newCategoryName = this.currentCategory.category_name;
            console.info('Edit Category component mounted');
        },
        methods: {
            goingToEditCategoryName: function () {
                this.editingCategoryName = true;
                setTimeout(()=> {
                    this.$refs['txt_edit_category'].focus();
                }, 10);
                this.$emit('edit-category-name');
            },
            cancelEditCategoryName: function () {
                this.newCategoryName = this.currentCategory.category_name;
                this.editingCategoryName = false;
                this.$refs['txt_edit_category'].blur();
                this.$emit('cancel-edit-category-name');
            },
            editCategory: function () {
                // category name same as before, quit
                if (this.currentCategory.category_name == this.newCategoryName) {
                    this.isEditingCategory = false;
                    this.editingCategoryName = false;
                    this.$refs['txt_edit_category'].blur();
                    this.$emit('cancel-edit-category-name');
                    return false;
                }

                this.isEditingCategory = true;
                this.errors = {};
                axios.put('/category/' + this.currentCategory.id, this.editCategoryData).then(response=> {
                    this.isEditingCategory = false;
                    if (response.data.status == true) {
                        this.editingCategoryName = false;
                    }
                    this.$emit('edited-category');
                }).catch(error=> {
                    this.isEditingCategory = false;
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
            currentCategory: function () {
                return this.editingCategory;
            },
            editCategoryData: function () {
                return {
                    category_name: this.newCategoryName
                };
            }
        }
    }
</script>

<style>
    .edit-category-wrapper {
        display: inline-block;
    }

    .btn-edit.btn-edit-category {
        margin-left: 30px;
        color: #aaa;
        font-size: 12px;
        cursor: pointer;
    }

</style>