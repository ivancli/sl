<template>
    <div>
        <div class="form-group">
            <div class="col-md-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="isActive">
                        &nbsp;
                        I'd like to receive the SpotLite Digest on my email.
                    </label>
                </div>
            </div>
        </div>
        <div v-show="isActive">
            <div class="form-group required">
                <label for="frequency" class="control-label col-md-3">Frequency</label>
                <div class="col-md-9">
                    <select id="frequency" class="form-control" v-model="frequency">
                        <option value="day">Daily</option>
                        <option value="week">Weekly</option>
                    </select>
                </div>
            </div>
            <div class="form-group required" v-show="showDailyOptions">
                <label for="time" class="control-label col-md-3">Time</label>
                <div class="col-md-9">
                    <select id="time" class="form-control" v-model="time">
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
            </div>
            <div class="form-group" v-show="showDailyOptions">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="weekdayOnly">
                            &nbsp;
                            Weekday only
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="showAll">
                            &nbsp;
                            Show all Product Page URLs in SpotLite Digest (even if the Category or Product price hasn't changed)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="isLoadingReport"></loading>
    </div>
</template>

<script>
    import loading from '../../fragments/loading/Loading.vue';

    export default{
        components: {
            loading,
        },
        data(){
            return {
                digestReport: null,
                isActive: false,
                frequency: 'day',
                time: '00:00:00',
                weekdayOnly: false,
                showAll: false,
                isLoadingReport: false,
            };
        },
        mounted(){
            console.info('EditDigest component mounted.');
            this.loadDigestReport(reports => {
                let digestReport = reports.filter(report => {
                    return report.report_type === 'digest';
                });
                if (digestReport.length > 0) {
                    this.digestReport = digestReport[0];
                    this.initSetReport();
                }
            });
        },
        watch: {
            digestReport(){
                this.emitUpdateDigest();
            },
            isActive(){
                this.emitUpdateDigest();
            },
            frequency(){
                this.emitUpdateDigest();
            },
            time(){
                this.emitUpdateDigest();
            },
            weekdayOnly(){
                this.emitUpdateDigest();
            },
            showAll(){
                this.emitUpdateDigest();
            }
        },
        methods: {
            loadDigestReport(callback){
                this.isLoadingReport = true;
                axios.get('/report').then(response => {
                    if (response.data.status === true) {
                        this.isLoadingReport = false;
                        if (typeof callback === 'function') {
                            callback(response.data.reports);
                        }
                    }
                }).catch(error => {
                    console.info(error.response);
                    this.isLoadingReport = false;
                })
            },
            initSetReport(){
                this.isActive = this.digestReport !== null;
                if (this.isActive) {
                    this.frequency = this.digestReport.frequency;
                    this.time = this.digestReport.time;
                    this.weekdayOnly = this.digestReport.weekday_only === 'y';
                    this.showAll = this.digestReport.show_all === 'y';
                }
            },
            emitUpdateDigest(){
                this.$emit('update-digest', this.updateReportRequestData);
            }
        },
        computed: {
            showDailyOptions(){
                return this.frequency === 'day';
            },
            updateReportRequestData(){
                switch (this.frequency) {
                    case 'day':
                        return {
                            report_id: this.digestReport !== null ? this.digestReport.id : null,
                            report_type: 'digest',
                            is_active: this.isActive,
                            frequency: this.frequency,
                            time: this.time,
                            weekday_only: this.weekdayOnly === true ? 'y' : 'n',
                            show_all: this.showAll === true ? 'y' : 'n',
                        };
                        break;
                    case 'week':
                        return {
                            report_id: this.digestReport !== null ? this.digestReport.id : null,
                            report_type: 'digest',
                            is_active: this.isActive,
                            frequency: this.frequency,
                            day: 1,
                            time: '00:00:00',
                            weekday_only: null,
                            show_all: this.showAll === true ? 'y' : 'n',
                        };
                        break;
                }
                return null;
            }
        }
    }
</script>

<style>

</style>