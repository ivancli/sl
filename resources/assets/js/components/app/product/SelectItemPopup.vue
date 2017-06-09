<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <div class="row" v-if="numberOfItemsWithPrice == 0">
                    <div class="col-sm-12">
                        <p>This price will be updated soon.</p>
                        <p>Note: If it doesn't come up in up to 48 hours, please contact us.</p>
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-sm-12">
                        <p>Please select the price you want to track from the following options:</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <form>
                                    <div class="form-group" v-for="item in items" v-if="item.recentPrice != null">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="selected_item" :value="item" v-model="selectedItem">
                                                {{ item.name }}
                                                <strong>${{ item.recentPrice | currency }}</strong>
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="text-right">
                    <button class="btn btn-primary btn-flat" href="#" @click.prevent="updateSite" :disabled="selectedItem == null" v-if="numberOfItemsWithPrice > 0">CONFIRM</button>
                    <a class="btn btn-default btn-flat" href="#" @click.prevent="hideModal">CANCEL</a>
                </div>
            </footer>
        </div>
        <loading v-if="isUpdatingSite"></loading>
    </div>
</template>

<script>
    import loading from '../../fragments/loading/Loading.vue';

    import currency from '../../../filters/currency';

    export default{
        props: [
            'editingSite'
        ],
        components: {
            loading
        },
        data(){
            return {
                isUpdatingSite: false,
                items: [],
                selectedItem: null,
            }
        },
        mounted(){
            console.info('SelectItemPopup is mounted.');
            this.selectedItem = this.site.item;
            this.loadItems();
        },
        methods: {
            loadItems(){
                axios.get(this.url.urls.item_index).then(response => {
                    if (response.data.status === true) {
                        this.items = response.data.items;
                    }
                }).catch(error => {
                    console.info(error.response);
                });
            },
            updateSite(){
                this.isUpdatingSite = true;
                axios.put(this.site.urls.item_update, this.updateSiteData).then(response => {
                    this.isUpdatingSite = false;
                    if (response.data.status == true) {
                        this.emitSelectedItem();
                    }
                }).catch(error => {
                    this.isUpdatingSite = false;
                    console.info(error.response);
                });
            },
            hideModal(){
                this.emitHideModal()
            },
            emitSelectedItem(){
                this.$emit('selected-item', this.selectedItem);
            },
            emitHideModal(){
                this.$emit('hide-modal');
            }
        },
        computed: {
            site(){
                return this.editingSite;
            },
            url(){
                return this.site.url;
            },
            updateSiteData(){
                return {
                    item_id: this.selectedItem.id
                };
            },
            numberOfItemsWithPrice(){
                return this.items.filter(item => {
                    return item.recentPrice !== null;
                }).length;
            }
        }
    }
</script>

<style>

</style>