<template>
    <tr>
        <td :class="rowClass">{{ product.category.category_name }}</td>
        <td :class="rowClass">{{ product.product_name }}</td>
        <td :class="rowClass">
            <div v-if="referencePrice != null">
                ${{ referencePrice | currency }}
            </div>
            <div v-else>n/a</div>
        </td>
        <td :class="rowClass">
            <div v-for="cheapestSite in cheapestSites">
                <a :href="cheapestSite.siteUrl" :class="rowClass">
                    <span v-if="cheapestSite.item != null && cheapestSite.item.sellerUsername != null">eBay: {{ cheapestSite.item.sellerUsername }}</span>
                    <span v-else>{{ siteNameOrDomain(cheapestSite.siteUrl) }}</span>
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
                return this.product.referencePrice;
            },
            cheapestSites(){
                return this.product.cheapestSites;
            },
            cheapestPrice(){
                return this.product.cheapestPrice;
            },
            secondCheapestPrice(){
                return this.product.secondCheapestPrice;
            },
            diffPrice(){
                if (this.cheapestPrice !== null && this.referencePrice !== null) {
                    if (this.cheapestPrice === this.referencePrice) {
                        if (this.cheapestSites.length > 0) {
                            return 0;
                        } else {
                            return this.secondCheapestPrice - this.referencePrice;
                        }
                    } else {
                        return this.cheapestPrice - this.referencePrice
                    }
                } else {
                    return null;
                }
            },
            diffPercent(){
                if (this.cheapestPrice !== null && this.referencePrice !== null) {
                    if (this.cheapestPrice === this.referencePrice) {
                        if (this.cheapestSites.length > 0) {
                            return 0;
                        } else {
                            return ((this.secondCheapestPrice - this.referencePrice) / this.referencePrice * 100).toFixed(2);
                        }
                    } else {
                        return ((this.cheapestPrice - this.referencePrice) / this.referencePrice * 100).toFixed(2);
                    }
                } else {
                    return null;
                }
            },
            rowClass(){
                if (this.product.referencePrice === this.product.cheapestPrice) {
                    return 'text-tiffany';
                }
                return '';
            }
        }
    }
</script>

<style>

</style>