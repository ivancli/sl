<template>
    <div class="text-center add-chart-to-dashboard-placeholder">
        <a href="#" class="text-muted" @click.prevent="onClickPlaceholder">
            <div class="vertical-align-middle placeholder-content-container">
                <div class="placeholder-text-icon-container">
                    <div class="placeholder-text-container" v-if="!needToRedirect">
                        Add Chart to Dashboard
                    </div>
                    <div class="placeholder-text-container" v-else>
                        Add Product to Track
                    </div>
                    <h1>
                        <i class="fa fa-plus-circle"></i>
                    </h1>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    export default
    {
        props: [
            'should-redirect'
        ],
        mounted(){
            console.info('Placeholder component mounted.');
        },
        methods: {
            onClickPlaceholder(){
                if (this.needToRedirect) {
                    window.location.href = '/product';
                } else {
                    this.emitAddNewWidget();
                }
            },
            emitAddNewWidget(){
                this.$emit('add-new-widget');
            }
        },
        computed: {
            needToRedirect(){
                return this.shouldRedirect;
            }
        }
    }
</script>

<style>
    .add-chart-to-dashboard-placeholder {
        border: 2px dashed #c0c0c0;
        height: 465px;
        background-color: #f7f7f7;
    }

    .add-chart-to-dashboard-placeholder .placeholder-content-container {
        width: 100%;
        height: 100%;
        display: table;
    }

    .placeholder-content-container .placeholder-text-icon-container {
        display: table-cell;
        vertical-align: middle;
    }

    .placeholder-content-container .placeholder-text-container {
        font-weight: bold;
        font-size: 20px;
    }
</style>