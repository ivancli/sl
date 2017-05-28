<template>
    <tr>
        <td>
            {{ alertCell }}
        </td>
        <td>
            {{ triggerCell }}
        </td>
        <td>
            {{ historicalAlert.email }}
        </td>
        <td>
            {{ historicalAlert.created_at | formatDateTime(datetimeFormat) }}
        </td>
    </tr>
</template>

<script>
    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        props: [
            'current-historical-alert',
        ],
        mounted(){
            console.info('SingleHistoricalAlertRow component mounted.');
        },
        computed: {
            alertType(){
                return this.historicalAlert.alert_type;
            },
            alertableType(){
                return this.historicalAlert.alertable_type;
            },
            category(){
                if (this.alertableType === 'category') {
                    return this.historicalAlert.alertable;
                }
                return null;
            },
            product(){
                if (this.alertableType === 'product') {
                    return this.historicalAlert.alertable;
                }
                return null;
            },
            alertPrice(){
                if (this.historicalAlert.comp_price !== null) {
                    return this.$options.filters.currency(this.historicalAlert.comp_price);
                }
                return null;
            },
            alertCell(){
                switch (this.alertType) {
                    case 'basic':
                        return 'Basic Alert';
                        break;
                    case 'advanced':
                        switch (this.alertableType) {
                            case 'product':
                                return 'Product Alert: ' + this.product.product_name;
                                break;
                            case 'category':
                                return 'Category Alert' + this.category.category_name;
                                break;
                        }
                        break;
                }
                return null;
            },
            triggerCell(){
                switch (this.historicalAlert.comp_type) {
                    case 'price_change':
                        return 'when price changed';
                        break;
                    case 'my_price':
                        return 'when my price was beaten'
                        break;
                    case 'custom':
                        let operator = '';
                        switch (this.historicalAlert.comp_operator) {
                            case '<':
                                operator = 'below';
                                break;
                            case '<=':
                                operator = 'below or equal to';
                                break;
                            case '>':
                                operator = 'above';
                                break;
                            case '>=':
                                operator = 'above or equal to';
                                break;
                            case '=':
                                break;
                        }
                        return 'when price is ' + operator + ' ' + this.alertPrice;
                        break;
                }
                return null;
            },
            historicalAlert(){
                return this.currentHistoricalAlert;
            },
            dateFormat(){
                return user.allPreferences.DATE_FORMAT;
            },
            timeFormat(){
                return user.allPreferences.TIME_FORMAT;
            },
            datetimeFormat(){
                return this.dateFormat + ' ' + this.timeFormat;
            },
        }
    }
</script>

<style>

</style>