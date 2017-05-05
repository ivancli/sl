<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <section class="modal-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>It appears that there are multiple options available in provided Product Page.</p>
                        <p>Please select from the following options:</p>
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
                    <button class="btn btn-primary btn-flat" href="#" @click.prevent="updateSite" :disabled="selectedItem == null">CONFIRM</button>
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
                    if (response.data.status == true) {
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
                        this.$emit('selected-item', this.selectedItem);
                    }
                }).catch(error => {
                    this.isUpdatingSite = false;
                    console.info(error.response);
                });
            },
            hideModal(){
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
            }
        }
    }
</script>

<style>

</style>