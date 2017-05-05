<template>
    <tr>
        <td v-text="permission.id"></td>
        <td v-text="permission.name"></td>
        <td v-text="permission.display_name"></td>
        <td>{{ permission.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ permission.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="permission.urls.show" class="text-muted">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="permission.urls.edit" class="text-muted">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeletePermission">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingPermission"></loading>
    </tr>
</template>

<script>
    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../fragments/loading/Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default {
        components: {
            deleteConfirmation,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'permission',
                    list: [
                        'All permission related information',
                    ],
                    active: false
                },
                isDeletingPermission: false,
            }
        },
        props: [
            'current-permission'
        ],
        mounted() {
            console.info('SinglePermissionRow component is mounted');
        },
        computed: {
            permission: function () {
                return this.currentPermission;
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
            onClickDeletePermission(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deletePermission()
            },
            deletePermission(){
                this.isDeletingPermission = true;
                axios.delete(this.permission.urls.delete).then(response=> {
                    this.isDeletingPermission = false;
                    if (response.data.status == true) {
                        this.$emit('reloadPermissions');
                    }
                }).catch(error=> {
                    this.isDeletingPermission = false;
                })
            }
        }
    }
</script>

<style>
</style>