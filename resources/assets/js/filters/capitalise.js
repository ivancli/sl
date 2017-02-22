/**
 * Created by ivan.li on 2/22/2017.
 */

import Vue from 'vue';

Vue.filter('capitalise', function (string) {
    if (typeof string == 'undefined') {
        return null;
    }
    return string.charAt(0).toUpperCase() + string.slice(1);
});