import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import App from './App.vue'
import router from './router'
import store from './store'

// https://www.npmjs.com/package/vue-material-design-icons
import "vue-material-design-icons/styles.css"
//import gridfx from './gridfx'

//Object.defineProperty(Vue.prototype, '$gridfx', { value: gridfx });

//var t = new gridfx;
//t._test();

Vue.use(BootstrapVue);
Vue.config.productionTip = false

const EventBus = new Vue();
export default EventBus;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
