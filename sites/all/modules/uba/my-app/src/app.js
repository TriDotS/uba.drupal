/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

//require('./bootstrap');
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');

Vue.component('datenexplorer', require('./components/DatenExplorer'));
Vue.component('datenpool', require('./components/DatenPool'));
Vue.component('ressourcenIndikatoren', require('./components/RessourcenIndikatoren'));
Vue.component('ressourcenAuswertung', require('./components/RessourcenAuswertung'));
Vue.component('ressourcenAuswertung2', require('./components/RessourcenAuswertung2'));
//
import Highcharts from 'highcharts';
import loadExportingImages from 'highcharts/modules/exporting';
import loadExportingData from 'highcharts/modules/export-data';
import loadExportingLocal from 'highcharts/modules/offline-exporting';
import VueHighcharts from 'vue-highcharts';

loadExportingImages(Highcharts);
loadExportingData(Highcharts);
loadExportingLocal(Highcharts);
Vue.use(VueHighcharts, { Highcharts });

const app = new Vue({
    el: '#app'
});