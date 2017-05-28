/**
 * Created by Ivan on 16/02/2017.
 */
import Vue from 'vue';

Vue.filter('domain', function (url) {
    if (typeof url === 'undefined' || url === null) {
        return null;
    }
    let domain;
    if (url.indexOf("://") > -1) {
        domain = url.split('/')[2];
    } else {
        domain = url.split('/')[0];
    }
    domain = domain.split(':')[0];
    return domain;
});