
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// Register plugin
require('./jquery');

require('./bootstrap');

require('./jquery.contact');

require('./jquery.fancybox.pack');

require('./jquery.flexslider-min');

require('./main');

//require('./modernizr');

require('./retina.min');

window.Vue = require('vue');
//require('./toastr');
// toastr
import toastr from 'toastr';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/ExampleComponent.vue'));
Vue.component('modal', require('./components/helpers/Modal.vue'));
Vue.component('modalstop', require('./components/helpers/ModalStop.vue'));
Vue.component('customer', require('./components/Customer.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('employee', require('./components/Employee.vue'));
Vue.component('bank', require('./components/Bank.vue'));
Vue.component('account', require('./components/Account.vue'));
Vue.component('banxico', require('./components/Banxico.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('paginate', require('./components/Paginate.vue'));
Vue.component('accountfile', require('./components/AccountFile.vue'));
Vue.component('transferfile', require('./components/TransferFile.vue'));
Vue.component('terms', require('./components/Terms.vue'));
Vue.component('contacts', require('./components/Contact.vue'));
Vue.component('customerregister', require('./components/CustomerRegister.vue'));
Vue.component('report', require('./components/Report.vue'));

const app = new Vue({
    el: '#app'
});
