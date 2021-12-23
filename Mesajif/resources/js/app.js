require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from "vue-router";
import routes from './routes'
import Vue from 'vue'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'
import InfiniteLoading from 'vue-infinite-loading';
import Vuex from 'vuex'


const moment = require('moment')
require('moment/locale/tr')

Vue.use(require('vue-moment'), {
    moment
})

Vue.use(Vuex)
Vue.use(InfiniteLoading, { /* options */});
Vue.use(VueRouter)
Vue.use(VueMaterial)
const app = new Vue({
    el: '#app',
    router: new VueRouter(routes)
});
