import Vue from 'vue'
import ESList from './es-list'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false;

new Vue({
  vuetify,
  render: h => h(ESList)
}).$mount('#app');
