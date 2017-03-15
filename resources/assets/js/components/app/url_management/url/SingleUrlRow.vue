<template>
    <tr>
        <td v-text="url.id"></td>
        <td>
            <a :href="url.full_path" v-text="url.full_path" target="_blank"></a>
        </td>
        <td v-text="url.status"></td>
        <td>{{ url.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ url.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="url.urls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="url.urls.edit" class="text-muted" title="edit">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a :href="url.urls.item_index" class="text-muted" title="edit items">
                <i class="glyphicon glyphicon-qrcode"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteUrl" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingUrl"></loading>
    </tr>
</template>

<script>
    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        components:{
            deleteConfirmation,
            loading,
        },
        props: [
            'current-url'
        ],
        data(){
            return {
                deleteParams: {
                    title: 'URL',
                    list: [
                        'All URL related categories, products and URLs',
                        'All URL related pricing information',
                    ],
                    active: false
                },
                isDeletingUrl: false
            };
        },
        mounted(){
            console.info('SingleUrlRow component is mounted.');
        },
        methods: {
            onClickDeleteUrl(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteUrl()
            },
            deleteUrl(){
                this.isDeletingUrl = true;
                axios.delete(this.url.urls.delete).then(response=> {
                    this.isDeletingUrl = false;
                    if (response.data.status == true) {
                        this.$emit('reloadUrls');
                    }
                }).catch(error=> {
                    this.isDeletingUrl = false;
                })
            }
        },
        computed: {
            url(){
                return this.currentUrl;
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