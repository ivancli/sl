<template>
    <tr>
        <td>
            {{ historicalReportCell }}
        </td>
        <td>
            <a :href="historicalReport.urls.show" :download="historicalReport.file_name">
                {{ historicalReport.file_name }}.xlsx
            </a>
        </td>
        <td>
            {{ historicalReport.created_at | formatDateTime(datetimeFormat) }}
        </td>
    </tr>
</template>

<script>
    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        props: [
            'current-historical-report',
        ],
        mounted(){
            console.info('SingleHistoricalReportRow component mounted.');
        },
        computed: {
            reportType(){
                return this.historicalReport.report_type;
            },
            reportableType(){
                return this.historicalReport.reportable_type;
            },
            category(){
                if (this.reportableType === 'category') {
                    return this.historicalReport.reportable;
                }
                return null;
            },
            product(){
                if (this.reportableType === 'product') {
                    return this.historicalReport.reportable;
                }
                return null;
            },
            historicalReportCell(){
                switch (this.reportableType) {
                    case 'product':
                        return 'Product Report: ' + this.product.product_name;
                        break;
                    case 'category':
                        return 'Category Report: ' + this.category.category_name;
                        break;
                }
                return null;
            },
            historicalReport(){
                return this.currentHistoricalReport;
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