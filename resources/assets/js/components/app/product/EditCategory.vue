<template>
    <div class="edit-category-wrapper">
        <span class="btn-edit btn-edit-category" @click.prevent="goingToEditCategoryName" v-show="!editingCategoryName">
            <span class="hidden-xs hidden-sm">Edit &nbsp;</span>
            <i class="fa fa-pencil-square-o"></i>
        </span>

        <div class="input-group sl-input-group" v-show="editingCategoryName">
            <input type="text" placeholder="Category Name" autocomplete="off" v-model="newCategoryName"
                   class="form-control sl-form-control input-lg category-name">
            <span class="input-group-btn">
                <button class="btn btn-default btn-flat btn-lg">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn btn-default btn-flat btn-lg">
                    <i class="fa fa-check"></i>
                </button>
            </span>
        </div>
    </div>
</template>

<script>
    export default{
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
            this.newCategoryName = this.currentCategory.name;
            console.info('Edit Category component mounted');
        },
        methods: {
            goingToEditCategoryName: function () {
                this.editingCategoryName = true;
                this.$emit('edit-category-name');
            },
            cancelEditCategoryName: function () {
                this.editingCategoryName = false;
                this.$emit('cancel-edit-category-name');
            }
        },
        computed: {
            currentCategory: function () {
                return this.editingCategory;
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
    }

</style>