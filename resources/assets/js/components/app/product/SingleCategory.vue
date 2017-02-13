<template>
    <table class="table table-condensed tbl-category">
        <thead>
        <tr>
            <th class="shrink category-th">
                <a class="btn-collapse" aria-expanded="true"><i class="fa fa-tag "></i></a>
            </th>
            <th class="category-th">
                <a class="text-muted category-name-link" href="#" v-text="category.name"></a>
                <!--<div class="input-group sl-input-group">-->
                <!--<input type="text" placeholder="Category Name" autocomplete="off" class="form-control sl-form-control input-lg category-name">-->
                <!--<span class="input-group-btn">-->
                <!--<button type="submit" class="btn btn-default btn-flat btn-lg">-->
                <!--<i class="fa fa-check"></i>-->
                <!--</button>-->
                <!--</span>-->
                <!--</div>-->

                <span class="btn-edit btn-edit-category">Edit &nbsp; <i class="fa fa-pencil-square-o"></i></span>
            </th>

            <th class="text-right action-cell category-th">
                <a href="#" class="btn-action btn-chart" title="chart">
                    <i class="fa fa-line-chart"></i>
                </a>
                <a href="#" class="btn-action btn-report" title="report">
                    <i class="fa fa-envelope ico-report-enabled"></i>
                </a>
                <a href="#" data-name="Apple iPad" class="btn-action btn-delete-category" title="delete">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </th>
            <th class="text-center vertical-align-middle" style="background-color: #d3d3d3;" width="70">
                <a class="text-muted btn-collapse" style="font-size: 35px;">
                    <i class="fa fa-angle-up"></i>
                </a>
            </th>
        </tr>
        <!--<tr>-->
        <!--<th></th>-->
        <!--<td colspan="3" class="category-th">-->
        <!--<div class="text-light">-->
        <!--Created-->
        <!--on {{category.created_at}}-->
        <!--<strong class="text-muted"><i>by {{category.owner.fullName}}</i></strong>-->
        <!--</div>-->
        <!--<div class="text-light">-->
        <!--Product URLs Tracked:-->
        <!--<strong><span class="lbl-site-usage text-muted" v-text="category.numberOfSites"></span></strong>-->
        <!--</div>-->
        <!--</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<th></th>-->
        <!--<th colspan="3" class="category-th action-cell add-item-cell">-->
        <!--<div class="add-item-block add-product-container">-->
        <!--<div class="add-item-label">-->
        <!--<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;-->
        <!--<span class="add-item-text">ADD PRODUCT</span>-->
        <!--</div>-->
        <!--<div class="add-item-controls">-->
        <!--<div class="row">-->
        <!--<div class="col-lg-8 col-md-7 col-sm-5 col-xs-4">-->
        <!--<form action="http://login.spotlite.com.au/product" method="post" class="frm-store-product" onsubmit="btnAddProductOnClick(this); return false;">-->
        <!--<input type="text" name="product_name" autocomplete="off" class="form-control txt-item txt-product-name">-->
        <!--</form>-->
        <!--</div>-->
        <!--<div class="col-lg-4 col-md-5 col-sm-7 col-xs-8 text-right">-->
        <!--<button class="btn btn-primary btn-flat">-->
        <!--ADD PRODUCT-->
        <!--</button>-->
        <!--&nbsp;&nbsp;-->
        <!--<button class="btn btn-default btn-flat btn-cancel-add-product">-->
        <!--CANCEL-->
        <!--</button>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</th>-->
        <!--</tr>-->
        </thead>
        <tbody>
        <!--<tr>-->
        <!--<td></td>-->
        <!--<td colspan="3" class="table-container">-->
        <!--<div class="collapse collapsible-category-div in" data-products-url="http://login.spotlite.com.au/product/category/313" aria-expanded="true">-->
        <!--<div class="row">-->
        <!--<div class="col-sm-12 text-center">-->
        <!--<div class="dotdotdot loading-products" style="margin: 20px auto; display: none;">-->

        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</td>-->
        <!--</tr>-->
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'category'
        ],
        mounted() {
            console.info(this.category);
            console.log('Add category component mounted.')
        }
    }
</script>

<style>
    .tbl-category {
        background-color: #f0f0f0;
    }

    .btn-collapse .fa-tag {
        font-size: 30px;
        margin-left: 10px;
    }

    .btn-action, .btn-collapse {
        color: #8c8c8c;
    }

    .category-name-link {
        font-size: 18px;
        line-height: 46px;
    }

    .btn-edit.btn-edit-category {
        margin-left: 30px;
        color: #aaa;
        font-size: 12px;
    }

    .btn-action {
        padding-left: 3px;
        padding-right: 3px;
        display: inline-block;
        font-size: 16px;
        vertical-align: middle;
    }

    .category-th.action-cell {
        padding-right: 25px;
        width: 200px;
    }

    .category-th {
        vertical-align: middle !important;
        height: 40px !important;
    }
</style>