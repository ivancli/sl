<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                Edit Report for {{ product.product_name }}
            </header>
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        Send me a report every
                        <select class="input-sm form-control sl-form-control" v-model="frequency">
                            <option value="day">day</option>
                            <option value="week">week</option>
                            <option value="month">month</option>
                        </select>
                        <div class="inline-block" v-if="showDailyOptions">
                            (
                            <select class="input-sm form-control sl-form-control" v-model="weekdayOnly">
                                <option value="">every day</option>
                                <option value="1">weekday only</option>
                            </select>
                            )
                            at
                            <select class="input-sm form-control sl-form-control" v-model="time">
                                <option value="00:00:00">12:00 am</option>
                                <option value="01:00:00">1:00 am</option>
                                <option value="02:00:00">2:00 am</option>
                                <option value="03:00:00">3:00 am</option>
                                <option value="04:00:00">4:00 am</option>
                                <option value="05:00:00">5:00 am</option>
                                <option value="06:00:00">6:00 am</option>
                                <option value="07:00:00">7:00 am</option>
                                <option value="08:00:00">8:00 am</option>
                                <option value="09:00:00">9:00 am</option>
                                <option value="10:00:00">10:00 am</option>
                                <option value="11:00:00">11:00 am</option>
                                <option value="12:00:00">12:00 pm</option>
                                <option value="13:00:00">1:00 pm</option>
                                <option value="14:00:00">2:00 pm</option>
                                <option value="15:00:00">3:00 pm</option>
                                <option value="16:00:00">4:00 pm</option>
                                <option value="17:00:00">5:00 pm</option>
                                <option value="18:00:00">6:00 pm</option>
                                <option value="19:00:00">7:00 pm</option>
                                <option value="20:00:00">8:00 pm</option>
                                <option value="21:00:00">9:00 pm</option>
                                <option value="22:00:00">10:00 pm</option>
                                <option value="23:00:00">11:00 pm</option>
                            </select>
                        </div>
                        <div class="inline-block" v-if="showWeeklyOptions">
                            on
                            <select class="input-sm form-control sl-form-control" v-model="day">
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                                <option value="0">Sunday</option>
                            </select>
                        </div>
                        <div class="inline-block" v-if="showMonthlyOptions">
                            on
                            <select class="input-sm form-control sl-form-control" v-model="date">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29 or last day of the month</option>
                                <option value="30">30 or last day of the month</option>
                                <option value="31">31 or last day of the month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <a class="btn btn-primary btn-flat" href="#" @click.prevent="onClickConfirm">CONFIRM</a>
                    <a class="btn btn-danger btn-flat" href="#" v-if="hasReport" @click.prevent="onClickDelete">DELETE</a>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
        <loading v-if="isLoadingReport || isSavingReport || isDeletingReport"></loading>
    </div>
</template>

<script>
    import loading from '../../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading
        },
        props: [
            'current-product'
        ],
        data(){
            return {
                report_id: null,
                frequency: 'day',
                weekdayOnly: "",
                time: "00:00:00",
                day: "1",
                date: "1",
                isLoadingReport: false,
                isSavingReport: false,
                isDeletingReport: false,
                report: null,
            }
        },
        mounted(){
            console.info('Product component mounted.');
            this.loadReport();
        },
        methods: {
            loadReport(){
                this.isLoadingReport = true;
                axios.get(this.product.urls.report_show).then(response => {
                    this.isLoadingReport = false;
                    if (response.data.status === true) {
                        if (response.data.report !== null) {
                            this.initSetReport(response.data.report);
                        }
                    }
                }).catch(error => {
                    this.isLoadingReport = false;
                    console.info(error.response);
                })
            },
            onClickConfirm(){
                if (this.report_id === null) {
                    this.storeReport();
                } else {
                    this.updateReport();
                }
            },
            onClickDelete(){
                this.deleteReport();
            },
            storeReport(){
                this.isSavingReport = true;
                axios.post('/report', this.saveReportRequestData).then(response => {
                    this.isSavingReport = false;
                    if (response.data.status === true) {
                        this.emitEditedReport();
                    }
                }).catch(error => {
                    this.isSavingReport = false;
                    console.info(error.response);
                })
            },
            updateReport(){
                this.isSavingReport = true;
                axios.put(this.report.urls.update, this.saveReportRequestData).then(response => {
                    this.isSavingReport = false;
                    if (response.data.status === true) {
                        this.emitEditedReport();
                    }
                }).catch(error => {
                    this.isSavingReport = false;
                    console.info(error.response);
                })
            },
            deleteReport(){
                this.isDeletingReport = true;
                axios.delete(this.report.urls.delete).then(response => {
                    this.isDeletingReport = false;
                    if (response.data.status === true) {
                        this.emitDeletedReport();
                    }
                }).catch(error => {
                    this.isDeletingReport = false;
                    console.info(error.response);
                });
            },
            initSetReport(report){
                this.report_id = report.id;
                this.frequency = report.frequency;
                this.weekdayOnly = report.weekday_only === 'y' ? '1' : '';
                this.time = report.time === null ? "00:00:00" : report.time;
                this.day = report.day === null ? "1" : report.day;
                this.date = report.date === null ? "1" : report.date;
                this.report = report;
            },
            hideModal(){
                this.emitCancelEdit();
            },
            emitEditedReport(){
                this.$emit('edited-report');
            },
            emitCancelEdit(){
                this.$emit('cancel-edit');
            },
            emitDeletedReport(){
                this.$emit('deleted-report');
            }
        },
        computed: {
            product(){
                return this.currentProduct;
            },
            showDailyOptions(){
                return this.frequency === 'day';
            },
            showWeeklyOptions(){
                return this.frequency === 'week';
            },
            showMonthlyOptions(){
                return this.frequency === 'month';
            },
            hasReport(){
                return this.report !== null;
            },
            saveReportRequestData(){
                switch (this.frequency) {
                    case 'day':
                        return {
                            report_type: 'product',
                            product_id: this.product.id,
                            frequency: this.frequency,
                            weekday_only: this.weekdayOnly === "1" ? 'y' : 'n',
                            time: this.time,
                            date: null,
                            day: null,
                        };
                        break;
                    case 'week':
                        return {
                            report_type: 'product',
                            product_id: this.product.id,
                            frequency: this.frequency,
                            day: this.day,
                            weekday_only: null,
                            date: null,
                            time: '00:00:00',
                        };
                        break;
                    case 'month':
                        return {
                            report_type: 'product',
                            product_id: this.product.id,
                            frequency: this.frequency,
                            date: this.date,
                            weekday_only: null,
                            day: null,
                            time: '00:00:00',
                        };
                        break;
                }
            }
        }
    }
</script>

<style>

</style>