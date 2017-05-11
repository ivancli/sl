<template>
    <div>
        <div class="form-group required">
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
        <div class="form-group required">
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
    </div>
</template>

<script>
    import {
        SET_DISPLAY
    } from '../../../../actions/action-types';

    export default{
        data(){
            return {
                dateFormat: null,
                timeFormat: null
            };
        },
        mounted(){
            console.info('Display.vue is mounted.');
            this.initSetDisplay();
        },
        watch: {
            dateFormat(){
                this.setDisplay();
            },
            timeFormat(){
                this.setDisplay();
            }
        },
        methods: {
            initSetDisplay(){
                this.dateFormat = user.allPreferences.DATE_FORMAT;
                this.timeFormat = user.allPreferences.TIME_FORMAT;
            },
            setDisplay(){
                this.$store.dispatch(SET_DISPLAY, {
                    display: this.setDisplayData
                });
            },
        },
        computed: {
            currentDateTime(){
                return new Date();
            },
            display(){
                return this.$store.getters.display;
            },
            setDisplayData(){
                return {
                    date_format: this.dateFormat,
                    time_format: this.timeFormat,
                };
            },
        }
    }
</script>

<style>

</style>