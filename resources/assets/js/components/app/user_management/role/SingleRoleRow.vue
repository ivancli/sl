<template>
    <tr>
        <td v-text="role.id"></td>
        <td v-text="role.name"></td>
        <td v-text="role.display_name"></td>
        <td v-text="role.numberOfUsers"></td>
        <td>{{ role.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ role.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="role.urls.show" class="text-muted">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="role.urls.edit" class="text-muted">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteRole">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingRole"></loading>
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
                    title: 'role',
                    list: [
                        'All role related information',
                    ],
                    active: false
                },
                isDeletingRole: false,
            }
        },
        props: [
            'current-role'
        ],
        mounted() {
            console.info('SingleRoleRow component is mounted');
        },
        computed: {
            role: function () {
                return this.currentRole;
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
            onClickDeleteRole(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteRole()
            },
            deleteRole(){
                this.isDeletingRole = true;
                axios.delete(this.role.urls.delete).then(response=> {
                    this.isDeletingRole = false;
                    if (response.data.status == true) {
                        this.$emit('reloadRoles');
                    }
                }).catch(error=> {
                    this.isDeletingRole = false;
                })
            }
        }
    }
</script>

<style>
</style>