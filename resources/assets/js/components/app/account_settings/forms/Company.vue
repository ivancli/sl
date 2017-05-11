<template>
    <div>

        <div class="form-group required">
            <label for="industry" class="control-label col-md-3">Industry</label>
            <div class="col-md-9">
                <select class="form-control" id="industry" v-model="industry">
                    <option value="">Please select</option>
                    <option value="Accommodations">Accommodations</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Advertising">Advertising</option>
                    <option value="Aerospace">Aerospace</option>
                    <option value="Agriculture &amp; Agribusiness">Agriculture &amp; Agribusiness</option>
                    <option value="Air Transportation">Air Transportation</option>
                    <option value="Apparel &amp; Accessories">Apparel &amp; Accessories</option>
                    <option value="Auto">Auto</option>
                    <option value="Banking">Banking</option>
                    <option value="Beauty &amp; Cosmetics">Beauty &amp; Cosmetics</option>
                    <option value="Biotechnology">Biotechnology</option>
                    <option value="Chemical">Chemical</option>
                    <option value="Communications">Communications</option>
                    <option value="Computer">Computer</option>
                    <option value="Construction">Construction</option>
                    <option value="Consulting">Consulting</option>
                    <option value="Consumer Electronics">Consumer Electrnics</option>
                    <option value="Education">Education</option>
                    <option value="Employment">Employment</option>
                    <option value="Energy">Energy</option>
                    <option value="Entertainment &amp; Recreation">Entertainment &amp; Recreation</option>
                    <option value="Fashion">Fashion</option>
                    <option value="Financial Services">Financial Services</option>
                    <option value="Fine Arts">Fine Arts</option>
                    <option value="Food &amp; Beverage">Food &amp; Beverage</option>
                    <option value="Health">Health</option>
                    <option value="Information">Information</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Journalism &amp; News">Journalism &amp; News</option>
                    <option value="Legal Services">Legal Services</option>
                    <option value="Manufacturing">Manufacturing</option>
                    <option value="Media &amp; Broadcasting">Media &amp; Broadcasting</option>
                    <option value="Medical Devices &amp; Supplies">Medical Devices &amp; Supplies</option>
                    <option value="Motion Pictures &amp; Video">Motion Pictures &amp; Video</option>
                    <option value="Music">Music</option>
                    <option value="Pharmaceutical">Pharmaceutical</option>
                    <option value="Public Administration">Public Administration</option>
                    <option value="Public Relations">Public Relations</option>
                    <option value="Publishing">Publishing</option>
                    <option value="Real Estate">Real Estate</option>
                    <option value="Retail">Retail</option>
                    <option value="Service">Service</option>
                    <option value="Sports">Sports</option>
                    <option value="Technology">Technology</option>
                    <option value="Telecommunications">Telecommunications</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Travel">Travel</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Video Game">Video Game</option>
                    <option value="Web Services">Web Services</option>
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label for="company_type" class="control-label col-md-3">Company type</label>
            <div class="col-md-9">
                <select class="form-control" id="company_type" v-model="companyType">
                    <option value="">Please select</option>
                    <option value="Retailer">Retailer</option>
                    <option value="Brand">Brand</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="company_url" class="control-label col-md-3">My Site URL</label>
            <div class="col-md-9">
                <input class="form-control" placeholder="e.g. http://www.example.com" type="text" id="company_url" v-model="companyUrl">
            </div>
        </div>
        <div class="form-group">
            <label for="ebay-username" class="control-label col-md-3">eBay Username</label>
            <div class="col-md-9">
                <input class="form-control" type="text" id="ebay-username" v-model="ebayUsername">
            </div>
        </div>
    </div>
</template>
<script>
    import {
        SET_COMPANY
    } from '../../../../actions/action-types';

    export default{
        data(){
            return {
                industry: null,
                companyType: null,
                companyUrl: null,
                ebayUsername: null,
            };
        },
        mounted(){
            console.info('Company.vue is mounted');
            this.initCompany();
        },
        watch: {
            industry(){
                this.setCompany();
            },
            companyType(){
                this.setCompany();
            },
            companyUrl(){
                this.setCompany();
            },
            ebayUsername(){
                this.setCompany();
            }
        },
        methods: {
            initCompany(){
                this.industry = user.allMetas.industry;
                this.companyType = user.allMetas.company_type;
                this.companyUrl = user.allMetas.company_url;
                this.ebayUsername = user.allMetas.ebay_username;
            },
            setCompany(){
                this.$store.dispatch(SET_COMPANY, {
                    company: this.setCompanyData
                });
            }
        },
        computed: {
            company(){
                return this.$store.getters.company;
            },
            setCompanyData(){
                return {
                    industry: this.industry,
                    company_type: this.companyType,
                    company_url: this.companyUrl,
                    ebay_username: this.ebayUsername,
                };
            },
        }
    }
</script>
<style>

</style>