<template>
    <tr>
        <td :class="rowClass">{{ product.category_name }}</td>
        <td :class="rowClass">{{ product.product_name }}</td>
        <td :class="rowClass">
            <div v-if="referencePrice != null">
                ${{ referencePrice | currency }}
            </div>
            <div v-else>n/a</div>
        </td>
        <td :class="rowClass">
            <div v-for="cheapestSite in cheapestSites">
                <a :href="cheapestSite.site_url" :class="rowClass" target="_blank">
                    {{ cheapestSite.display_name }}
                </a>
            </div>
        </td>
        <td :class="rowClass">
            <div class="text-right" v-if="cheapestPrice != null">
                ${{ cheapestPrice | currency }}
            </div>
            <div class="text-center" v-else>n/a</div>
        </td>
        <td :class="rowClass">
            <div class="text-right f-w-bold" v-if="diffPrice != null">
                <div v-if="diffPrice > 0" class="text-success">
                    <i class="fa fa-plus"></i>
                    ${{ diffPrice | currency }}
                </div>
                <div v-else-if="diffPrice < 0" class="text-danger">
                    <i class="fa fa-minus"></i>
                    ${{ diffPrice | currency }}
                </div>
                <div v-else>
                    0
                </div>
            </div>
            <div class="text-center" v-else>n/a</div>
        </td>
        <td :class="rowClass">
            <div class="text-right f-w-bold" v-if="diffPercent != null">
                <div v-if="diffPercent > 0" class="text-success">
                    <i class="fa fa-plus"></i>
                    {{ diffPercent | currency }}%
                </div>
                <div v-else-if="diffPercent < 0" class="text-danger">
                    <i class="fa fa-minus"></i>
                    {{ diffPercent | currency }}%
                </div>
                <div v-else>
                    0
                </div>
            </div>
            <div class="text-center f-w-bold" v-else>
                n/a
            </div>
        </td>
    </tr>
</template>

<script>
    import currency from '../../../filters/currency';
    import domain from '../../../filters/domain';

    export default{
        props: [
            'current-product'
        ],
        mounted(){
            console.info('SingleProductRow component mounted.');
        },
        data(){
            return {};
        },
        methods: {
            siteNameOrDomain(url){
                let siteDomain = this.$options.filters.domain(url);

                let userDomains = this.userDomains.filter(userDomain => {
                    let domain = this.$options.filters.domain(userDomain.domain);
                    return siteDomain.indexOf(domain) > -1;
                });
                if (userDomains.length > 0) {
                    let userDomain = userDomains[0];
                    if (userDomain.alias !== null && userDomain.alias.length > 0) {
                        return userDomain.alias;
                    }
                }
                return siteDomain;
            }
        },
        computed: {
            userDomains(){
                return this.$store.getters.userDomains;
            },
            product(){
                return this.currentProduct;
            },
            referencePrice(){
                if (typeof this.product.reference_recent_price !== 'undefined') {
                    return parseFloat(this.product.reference_recent_price);
                } else {
                    return null;
                }
            },
            cheapestSites(){
                if (this.product.cheapest_site_url !== null) {
                    let sites = this.product.cheapest_site_url.split('$ $');
                    let output = [];
                    sites.forEach(site => {
                        let siteInfo = site.split('$#$');
                        let site_url = siteInfo[0];
                        let displayName = null;
                        if (typeof siteInfo[1] !== 'undefined' && siteInfo[1] !== '') {
                            displayName = siteInfo[1];
                        } else {
                            let matchedDomains = this.userDomains.filter(domain => {
                                return site_url.indexOf(domain.domain) > 0
                            });
                            if (matchedDomains.length > 0 && matchedDomains[0].alias !== null) {
                                displayName = matchedDomains[0].alias;
                            } else {
                                displayName = this.$options.filters.domain(site_url);
                            }
                        }

                        output.push({
                            site_url: site_url,
                            display_name: displayName,
                        })
                    });
                    return output;
                }
                return this.product.cheapestSites;
            },
            cheapestPrice(){
                if (typeof this.product.cheapest_recent_price !== 'undefined') {
                    return parseFloat(this.product.cheapest_recent_price);
                } else {
                    return null;
                }
            },
            secondCheapestPrice(){
                if (typeof this.product.second_cheapest_recent_price !== 'undefined') {
                    return parseFloat(this.product.second_cheapest_recent_price);
                } else {
                    return null;
                }
            },
            diffPrice(){
                if (this.cheapestPrice !== null && this.referencePrice !== null) {
                    if (this.cheapestPrice === this.referencePrice) {
                        if (this.cheapestSites.length > 1) {
                            return 0;
                        } else {
                            return this.secondCheapestPrice - this.referencePrice;
                        }
                    } else {
                        return this.cheapestPrice - this.referencePrice
                    }
                }
                return null;
            },
            diffPercent(){
                if (this.cheapestPrice !== null && this.referencePrice !== null) {
                    if (this.cheapestPrice === this.referencePrice) {
                        if (this.cheapestSites.length > 1) {
                            return 0;
                        } else {
                            return ((this.secondCheapestPrice - this.referencePrice) / this.referencePrice * 100).toFixed(2);
                        }
                    } else {
                        return ((this.referencePrice - this.cheapestPrice) / this.referencePrice * 100).toFixed(2);
                    }
                } else {
                    return null;
                }
            },
            rowClass(){
                if (parseFloat(this.referencePrice) === parseFloat(this.cheapestPrice)) {
                    return 'text-tiffany';
                }
                return '';
            }
        }
    }
</script>

<style>

</style>