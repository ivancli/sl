<template>

    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="p-10">
                <h4 class="lead">Date Time</h4>
                <hr>
                <div class="row">
                    <div class="col-md-offset-3">
                        <ul class="text-danger errors-container p-b-10 p-l-20" v-if="Object.keys(errors).length > 0">
                            <li v-for="error in errors">
                                <div v-if="error.constructor != Array" v-text="error"></div>
                                <div v-else v-for="message in error" v-text="message"></div>
                            </li>
                        </ul>
                        <ul class="p-b-10 p-l-20 success-container" v-if="successMsg != ''">
                            <li v-text="successMsg"></li>
                        </ul>
                    </div>
                </div>
                <form class="sl-form-horizontal form-horizontal" onsubmit="return false;">
                    <div class="form-group">
                        <label for="sel-date-format" class="col-md-3 control-label">Date format</label>
                        <div class="col-md-9">
                            <select id="sel-date-format" class="form-control" v-model="dateFormat">
                                <option value="">Please select</option>
                                <option value="j M y">{{ currentDateTime | formatDateTime('j M y') }}</option>
                                <option value="Y-m-d">{{ currentDateTime | formatDateTime('Y-m-d') }}</option>
                                <option value="d F">{{ currentDateTime | formatDateTime('d F') }}</option>
                                <option value="j M Y">{{ currentDateTime | formatDateTime('j M Y') }}</option>
                                <option value="Ymd">{{ currentDateTime | formatDateTime('Ymd') }}</option>
                                <option value="jS of F Y">{{ currentDateTime | formatDateTime('jS of F Y') }}</option>
                                <option value="j F Y">{{ currentDateTime | formatDateTime('j F Y') }}</option>
                                <option value="F j, Y">{{ currentDateTime | formatDateTime('F j, Y') }}</option>
                                <option value="d/m/Y">{{ currentDateTime | formatDateTime('d/m/Y') }}</option>
                                <option value="m/d/Y">{{ currentDateTime | formatDateTime('m/d/Y') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sel-time-format" class="col-md-3 control-label">Time format</label>
                        <div class="col-md-9">
                            <select id="sel-time-format" class="form-control" v-model="timeFormat">
                                <option value="">Please select</option>
                                <option value="g:i a">{{ currentDateTime | formatDateTime('g:i a') }}</option>
                                <option value="h:i a">{{ currentDateTime | formatDateTime('h:i a') }}</option>
                                <option value="g:i A">{{ currentDateTime | formatDateTime('g:i A') }}</option>
                                <option value="h:i A">{{ currentDateTime | formatDateTime('h:i A') }}</option>
                                <option value="H:i">{{ currentDateTime | formatDateTime('H:i') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-flat" @click.prevent="updatePreference">UPDATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <loading v-if="isUpdatingPreference"></loading>
    </div>
</template>

<script>
    import loading from '../../Loading.vue';
    import formatDateTime from '../../../filters/formatDateTime';

    export default{
        components: {
            loading
        },
        data(){
            return {
                dateFormat: '',
                timeFormat: '',
                isUpdatingPreference: false,
                successMsg: '',
                errors: {},
            };
        },
        mounted(){
            console.info("DisplaySettings component is mounted");
            this.setInitUserPreference();
        },
        methods: {
            setInitUserPreference: function () {
                this.dateFormat = user.allPreferences.DATE_FORMAT;
                this.timeFormat = user.allPreferences.TIME_FORMAT;
            },
            updatePreference: function () {
                this.clearErrors();
                this.clearSuccessMsg();
                this.isUpdatingPreference = true;
                axios.put(user.preferenceUrls.update, this.updateUserPreferenceData).then(response=> {
                    this.isUpdatingPreference = false;
                    if (response.data.status == true) {
                        this.setSuccessMsg();
                    }
                }).catch(error=> {
                    this.isUpdatingPreference = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            },
            setSuccessMsg: function () {
                this.successMsg = "Display settings are updated"
            },
            clearSuccessMsg: function () {
                this.successMsg = '';
            }
        },
        computed: {
            currentDateTime(){
                return new Date();
            },
            updateUserPreferenceData(){
                return {
                    DATE_FORMAT: this.dateFormat,
                    TIME_FORMAT: this.timeFormat,
                }
            }
        }
    }
</script>

<style>

</style>