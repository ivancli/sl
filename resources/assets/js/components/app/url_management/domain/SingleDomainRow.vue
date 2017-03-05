<template>
    <tr>
        <td v-text="domain.id"></td>
        <td v-text="domain.name"></td>
        <td>
            <a :href="domain.full_path" v-text="domain.full_path" target="_blank"></a>
        </td>
        <td>{{ domain.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ domain.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="domain.modelUrls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="domain.modelUrls.edit" class="text-muted" title="edit">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a :href="domain.modelUrls.meta_edit" class="text-muted" title="edit meta">
                <i class="glyphicon glyphicon-qrcode"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteDomain" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingDomain"></loading>
    </tr>
</template>

<script>
    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default {
        components: {
            deleteConfirmation,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'domain',
                    list: [
                        'All domain related categories, products and URLs',
                        'All domain related pricing information',
                    ],
                    active: false
                },
                isDeletingDomain: false,
            }
        },
        props: [
            'current-domain'
        ],
        mounted() {
            console.info('SingleDomainRow component is mounted');
        },
        computed: {
            domain: function () {
                return this.currentDomain;
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
        },
        methods: {
            onClickDeleteDomain(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteDomain()
            },
            deleteDomain(){
                this.isDeletingDomain = true;
                axios.delete(this.domain.urls.delete).then(response=> {
                    this.isDeletingDomain = false;
                    if (response.data.status == true) {
                        this.$emit('reloadDomains');
                    }
                }).catch(error=> {
                    this.isDeletingDomain = false;
                })
            }
        }
    }
</script>

<style>
</style>