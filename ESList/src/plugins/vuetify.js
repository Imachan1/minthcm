import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
    breakpoint: {
        thresholds: {
            xs: 400,
            sm: 700,
            md: 1000,
            lg: 1400,
        }
    },
});
