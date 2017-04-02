<template>
    <tr>
        <td v-text="itemMeta.id"></td>
        <td v-text="itemMeta.element"></td>
        <td v-text="itemMeta.value"></td>
        <td v-text="itemMeta.historical_type"></td>
        <td>{{ itemMeta.created_at | formatDateTime(datetimeFormat) }}</td>
        <td>{{ itemMeta.updated_at | formatDateTime(datetimeFormat) }}</td>
        <td class="text-center">
            <a :href="itemMeta.urls.show" class="text-muted" title="view">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="edit" @click.prevent="onClickEditItemMeta">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            &nbsp;
            <a href="#" class="text-muted" title="edit configurations" @click.prevent="onClickEditItemMetaConfs">
                <i class="glyphicon glyphicon-cog"></i>
            </a>
            &nbsp;
            <a href="#" class="text-danger" @click.prevent="onClickDeleteItemMeta" title="delete">
                <i class="glyphicon glyphicon-trash"></i>
            </a>
        </td>
        <edit-popup :is-active="isEditingItemMeta" :item-meta="itemMeta" @hide-modal="hideEditPopup" @updated-item-meta="updatedItemMeta"></edit-popup>
        <edit-confs-popup :is-active="isEditingItemMetaConfs" :item-meta="itemMeta" @hide-modal="hideEditItemMetaConfsPopup" @updated-item-meta-confs="updatedItemMetaConfs"></edit-confs-popup>
        <delete-confirmation v-if="deleteParams.active" :deleteParams="deleteParams" @cancelDelete="cancelDelete" @confirmDelete="confirmDelete"></delete-confirmation>
        <loading v-if="isDeletingItemMeta"></loading>
    </tr>
</template>

<script>
    import editPopup from './EditPopup.vue';
    import editConfsPopup from '../item_meta_conf/EditPopup.vue';

    import deleteConfirmation from '../../../fragments/modals/DeleteConfirmation.vue';
    import loading from '../../../Loading.vue';

    import formatDateTime from '../../../../filters/formatDateTime';

    export default{
        components: {
            editPopup,
            editConfsPopup,
            deleteConfirmation,
            loading,
        },
        data(){
            return {
                deleteParams: {
                    title: 'item meta',
                    list: [
                        'All configuration associated with this meta',
                        'All historical prices associated with this meta',
                    ],
                    active: false
                },
                isDeletingItemMeta: false,
                isEditingItemMeta: false,
                isEditingItemMetaConfs: false,
            }
        },
        props: [
            'current-item-meta',
            'item'
        ],
        mounted(){
            console.info('SingleItemMetaRow component mounted.');
        },
        computed: {
            itemMeta(){
                return this.currentItemMeta;
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
            onClickEditItemMeta(){
                this.isEditingItemMeta = true;
            },
            hideEditPopup(){
                this.isEditingItemMeta = false;
            },
            updatedItemMeta(){
                this.$emit('reloadItemMetas');
                this.hideEditPopup();
            },
            onClickEditItemMetaConfs(){
                this.isEditingItemMetaConfs = true;
            },
            hideEditItemMetaConfsPopup(){
                this.isEditingItemMetaConfs = false;
            },
            updatedItemMetaConfs(){
                this.hideEditItemMetaConfsPopup();
            },
            onClickDeleteItemMeta(){
                this.deleteParams.active = true;
            },
            cancelDelete(){
                this.deleteParams.active = false;
            },
            confirmDelete(){
                this.deleteParams.active = false;
                this.deleteItemMeta()
            },
            deleteItemMeta(){
                this.isDeletingItemMeta = true;
                axios.delete(this.itemMeta.urls.delete).then(response => {
                    this.isDeletingItemMeta = false;
                    if (response.data.status == true) {
                        this.$emit('reloadItemMetas');
                    }
                }).catch(error => {
                    this.isDeletingItemMeta = false;
                })
            }
        }
    }
</script>

<style>

</style>