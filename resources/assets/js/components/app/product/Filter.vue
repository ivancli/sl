<template>
    <div class="search-input">
        <div class="ico-search">
            <div class="search-icon">
                <i class="fa fa-search text-muted"></i>
            </div>
        </div>
        <input type="text" class="form-control general-search-input" autocomplete="off"
               placeholder="ENTER THE CATEGORY OR PRODUCT YOU'D LIKE TO SEARCH">
        <div class="btn-clear-search">
            <div class="clear-icon">
                <a href="#">
                    <i class="fa fa-times text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.info('Filter component mounted.')
        }
    }
</script>

<style>
    .search-input .ico-search {
        height: 100%;
        width: 40px;
        position: absolute;
        text-align: center;
    }

    .search-input .ico-search .search-icon {
        position: relative;
        top: 48%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        font-size: 16px;
    }

    .general-search-input {
        border-radius: 20px;
        height: 45px;
        padding-left: 35px;
        padding-right: 35px;
    }

    .search-input .btn-clear-search {
        height: 100%;
        width: 70px;
        position: absolute;
        text-align: center;
        top: 0;
        right: 0;
        display: none;
    }

    .search-input .btn-clear-search .clear-icon {
        position: relative;
        top: 48%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
    }
</style>