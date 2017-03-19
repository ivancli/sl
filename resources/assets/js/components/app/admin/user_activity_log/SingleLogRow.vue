<template>
    <tr>
        <td v-text="log.id"></td>
        <td>
            <a href="#" v-text="log.user.fullName"></a>
        </td>
        <td v-text="log.activity"></td>
        <td>{{ log.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ log.updated_at | formatDateTime(datetimeFormat) }}</td>
    </tr>
</template>

<script>
    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        props: [
            'current-log'
        ],
        mounted(){
            console.info('SingleLogRow is mounted.');
        },
        computed: {
            log(){
                return this.currentLog;
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