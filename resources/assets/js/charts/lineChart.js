/**
 * Created by Ivan on 15/05/2017.
 */
import {Line} from 'vue-chartjs';

export default Line.extend({
    props: ['data', 'options'],
    mounted () {
        this.renderChart(this.data, this.options)
    }
});