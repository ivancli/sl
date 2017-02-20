<template>
    <tr>
        <td v-text="group.id"></td>
        <td v-text="group.name"></td>
        <td v-text="group.display_name"></td>
        <td v-text="group.numberOfUsers"></td>
        <td>{{ group.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ group.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="group.urls.show" class="text-muted">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="group.urls.edit" class="text-muted">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteGroup">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingGroup"></loading>
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
                    title: 'group',
                    list: [
                        'All group related categories, products and URLs',
                        'All group related pricing information',
                    ],
                    active: false
                },
                isDeletingGroup: false,
            }
        },
        props: [
            'current-group'
        ],
        mounted() {
            console.info('SingleGroupRow component is mounted');
        },
        computed: {
            group: function () {
                return this.currentGroup;
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
            onClickDeleteGroup(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteGroup()
            },
            deleteGroup(){
                this.isDeletingGroup = true;
                axios.delete(this.group.urls.delete).then(response=> {
                    this.isDeletingGroup = false;
                    if (response.data.status == true) {
                        this.$emit('reloadGroups');
                    }
                }).catch(error=> {
                    this.isDeletingGroup = false;
                })
            }
        }
    }
</script>

<style>
</style>