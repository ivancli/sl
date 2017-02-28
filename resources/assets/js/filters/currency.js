/**
 * Created by ivan.li on 2/28/2017.
 */
import Vue from 'vue';

Vue.filter('currency', function (number) {
    if (typeof number == 'undefined') {
        return null;
    }
    var c = 2,
        d = ".",
        t = ",",
        s = number < 0 ? "-" : "",
        i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "");
});