<template>
    <div class="edit-category-wrapper" v-show="goingToEditSite">
        <div class="input-group sl-input-group">
            <input type="text" placeholder="Product Page URL" v-model="newSiteURL" autocomplete="off" class="form-control sl-form-control txt-edit-site-url input-sm" ref="txt_edit_site" tabindex="-1">
            <span class="input-group-btn input-group-sm">
                <button class="btn btn-primary btn-flat btn-sm" @click.prevent="editSite">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn btn-default btn-flat btn-sm" @click.prevent="cancelEditSiteURL">
                    <i class="fa fa-times"></i>
                </button>
            </span>
        </div>
        <error-modal :modal-errors="errors" @hideErrorModal="clearErrors"></error-modal>
    </div>
</template>

<script>
    import errorModal from '../../fragments/modals/Error.vue';

    export default{
        components: {
            errorModal
        },
        props: [
            'editing-site',
            'going-to-edit-site'
        ],
        data() {
            return {
                editingSiteURL: false, //determine visibility of panel
                newSiteURL: '', //site url input value
                isEditingSite: false, //promise of form submission,
                errors: {},
            }
        },
        mounted(){
            this.newSiteURL = this.currentSite.siteUrl;
            console.info('Edit Site component mounted');
        },
        watch: {
            goingToEditSite(val){
                this.editingSiteURL = val;
                if (val == true) {
                    this.$refs['txt_edit_site'].focus();
                } else {
                    this.$refs['txt_edit_site'].blur();
                }
            }
        },
        methods: {
            cancelEditSiteURL: function () {
                this.newSiteURL = this.currentSite.siteUrl;
                this.editingSiteURL = false;
                this.$refs['txt_edit_site'].blur();
                this.$emit('cancel-edit-site-url');
            },
            editSite: function () {
                // site url same as before, quit
                if (this.currentSite.siteUrl == this.newSiteURL) {
                    this.isEditingSite = false;
                    this.editingSiteURL = false;
                    this.$refs['txt_edit_site'].blur();
                    this.$emit('cancel-edit-site-url');
                    return false;
                }

                this.isEditingSite = true;
                this.clearErrors();
                axios.put('/site/' + this.currentSite.id, this.editSiteData).then(response => {
                    this.isEditingSite = false;
                    if (response.data.status == true) {
                        this.editingSiteURL = false;
                    }
                    this.$emit('edited-site');
                }).catch(error => {
                    this.isEditingSite = false;
                    if (error.response && error.response.status == 422 && error.response.data) {
                        this.errors = error.response.data;
                    }
                })
            },
            clearErrors: function () {
                this.errors = {};
            }
        },
        computed: {
            currentSite: function () {
                return this.editingSite;
            },
            editSiteData: function () {
                return {
                    full_path: this.newSiteURL,
                    product_id: this.currentSite.product_id,
                };
            },
        }
    }
</script>

<style>
    .btn-edit-site {
        cursor: pointer;
    }

    input.form-control.txt-edit-site-url {
        width: 135px;
    }
</style>