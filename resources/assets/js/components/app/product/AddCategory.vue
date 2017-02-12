<template>
<div class="row">
    <div class="col-sm-12">
        <div class="add-item-block add-category-container">
            <div class="add-item-label" style="display: block;">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;
                <span class="add-item-text">ADD CATEGORY</span>
            </div>
            <!--<div class="add-item-controls" style="display: none;">-->
                <!--<div class="row">-->
                    <!--<div class="col-lg-8 col-md-7 col-sm-5 col-xs-4">-->
                        <!--<form action="http://login.spotlite.com.au/category" method="post" class="frm-store-category" onsubmit="btnAddCategoryOnClick(this); return false;">-->
                            <!--<input type="text" id="txt-category-name" autocomplete="off" class="form-control txt-item" name="category_name">-->
                        <!--</form>-->
                    <!--</div>-->
                    <!--<div class="col-lg-4 col-md-5 col-sm-7 col-xs-8 text-right">-->
                        <!--<button class="btn btn-primary btn-flat" onclick="btnAddCategoryOnClick(this); event.stopPropagation(); event.preventDefault();">-->
                            <!--ADD CATEGORY-->
                        <!--</button>-->
                        <!--&nbsp;&nbsp;-->
                        <!--<button class="btn btn-default btn-flat" id="btn-cancel-add-category" onclick="cancelAddCategory(this); event.stopPropagation(); event.preventDefault();">-->
                            <!--CANCEL-->
                        <!--</button>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        </div>
    </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Add category component mounted.')
        }
    }
</script>

<style>

</style>