<template>
    <tr class="site-wrapper" data-site-id="1539" data-site-edit-url="http://login.spotlite.com.au/site/1539/edit" data-site-alert-url="http://login.spotlite.com.au/alert/site/1539/edit"
        data-site-product-show-url="http://login.spotlite.com.au/product/559" data-site-update-my-price-url="http://login.spotlite.com.au/site/1539/my_price">
        <td class="site-url vertical-align-middle">
            <a href="https://www.jbhifi.com.au/computers-tablets/tablets/apple/apple-ipad-mini-2-32gb-wi-fi-silver/484271/" target="_blank" class="text-muted site-url-link" data-toggle="popover"
               data-container="body" data-trigger="hover" data-content="https://www.jbhifi.com.au/computers-tablets/tablets/apple/apple-ipad-mini-2-32gb-wi-fi-silver/484271/" data-original-title=""
               title="">
                www.jbhifi.com.au
            </a>

            <!--<div class="frm-edit-site-url input-group sl-input-group" style="display: none;">-->
            <!--<input type="text" name="site_url" placeholder="Site URL" autocomplete="off" class="form-control sl-form-control txt-site-url">-->
            <!--<span class="input-group-btn">-->
            <!--<button type="submit" class="btn btn-default btn-flat" data-url="http://login.spotlite.com.au/site/1539" >-->
            <!--<i class="fa fa-check"></i>-->
            <!--</button>-->
            <!--</span>-->
            <!--</div>-->
            <div class="btn-edit btn-edit-site pull-right">
                <div class="btn-edit-align-middle">
                    Edit &nbsp;
                    <i class="fa fa-pencil-square-o"></i>
                </div>
            </div>
        </td>
        <td align="center">
            <a href="#" class="btn-my-price">
                <i class="fa fa-check-circle-o text-primary"></i>
            </a>
        </td>
        <td>
            <div class="text-right">
                $368.00
            </div>
        </td>
        <td>
            <div class="text-right">
                <div class="p-r-30">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="hidden-xs">
            <div class="text-right">
                <div class="p-r-10">
                    <strong><i class="fa fa-minus"></i></strong>
                </div>
            </div>
        </td>
        <td class="hidden-xs" style="padding-left: 20px;">
            <div class="p-l-30">
                <strong><i class="fa fa-minus"></i></strong>
            </div>
        </td>
        <td>
            <span title="2017-02-14 3:10 pm" data-toggle="tooltip">
                2017-02-14
                <span class="hidden-xs hidden-sm">3:10 pm</span>
            </span>
        </td>
        <td>
            <div title="2017-01-16 12:16 pm">
                2017-01-16
            </div>
        </td>
        <td class="text-right action-cell">
            <a href="#" class="btn-action" title="chart">
                <i class="fa fa-line-chart"></i>
            </a>
            <a href="#" class="btn-action" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
    </tr>
</template>

<script>
    export default {
        props: [
            'current-site'
        ],
        mounted() {

        },
        computed: {
            site(){
                return this.currentSite;
            }
        }
    }
</script>

<style>
    .btn-edit.btn-edit-site {
        color: #aaa;
        font-size: 12px;
    }
    .btn-edit.btn-edit-site {
        position: absolute;
        right: 0;
        top: 0;
        margin: 0 !important;
        height: 100%;
    }

    .btn-edit.btn-edit-site .btn-edit-align-middle {
        position: relative;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    tr.site-wrapper td.site-url {
        position: relative;
        padding-right: 50px !important;
    }
</style>