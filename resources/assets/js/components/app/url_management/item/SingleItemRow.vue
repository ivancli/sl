<template>
    <tr>
        <td v-text="item.id"></td>
        <td v-text="item.name"></td>
        <td v-text="item.is_active"></td>
        <td>{{ item.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ item.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="item.urls.show" class="text-muted">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a :href="item.urls.edit" class="text-muted">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteItem">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingItem"></loading>
    </tr>
</template>

<script>
    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        components: {
            deleteConfirmation,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'item',
                    list: [
                        'All historic prices associated with this item',
                        'All meta data associated with this item',
                    ],
                    active: false
                },
                isDeletingItem: false,
            }
        },
        props: [
            'current-item'
        ],
        mounted(){
            console.info('SingleItemRow component mounted.');
        },
        computed: {
            item(){
                return this.currentItem;
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
            onClickDeleteItem(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteItem()
            },
            deleteItem(){
                this.isDeletingItem = true;
                axios.delete(this.item.urls.delete).then(response => {
                    this.isDeletingItem = false;
                    if (response.data.status == true) {
                        this.$emit('reloadItems');
                    }
                }).catch(error => {
                    this.isDeletingItem = false;
                })
            }
        }
    }
</script>

<style>

</style>