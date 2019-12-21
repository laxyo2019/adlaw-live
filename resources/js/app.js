require('./bootstrap')
require('vue-multiselect/dist/vue-multiselect.min.css')

import Multiselect from 'vue-multiselect'
import PrettyCheckbox from 'pretty-checkbox-vue';
import VueSweetalert2 from 'vue-sweetalert2'
import Toasted from 'vue-toasted';

import 'sweetalert2/dist/sweetalert2.min.css';

// import VueFriendlyIframe from 'vue-friendly-iframe';

window.Vue = require('vue')

// Permissions Mixin
// import Permissions from './mixins/Permissions';
// Vue.mixin(Permissions);
Vue.use(Toasted, {
	iconPack: 'fontawesome'
});

Vue.use(PrettyCheckbox);
Vue.use(VueSweetalert2);
Vue.use(require('vue-moment'));


// Reusable Modules
Vue.component('multiselect', Multiselect)
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('card-component', require('./components/re-usable/Card.vue').default)
Vue.component('countdown-timer', require('./components/re-usable/Timer.vue').default)
//Vue.component('notification-component', require('./components/re-usable/NotificationComponent.vue').default)
Vue.component('users-online', require('./components/re-usable/UsersOnline.vue').default)
Vue.component('media-component', require('./components/re-usable/MediaComponent.vue').default)
Vue.component('comments-component', require('./components/re-usable/CommentsComponent.vue').default)
Vue.component('user-selector', require('./components/re-usable/UserSelector.vue').default)


Vue.component('agenda', require('./modules/agenda/Index.vue').default)

// Docs
Vue.component('docs-home', require('./modules/docs/Home.vue').default)
Vue.component('stack-component', require('./modules/docs/Stack.vue').default)
//Admin
Vue.component('filestack-mgmt', require('./modules/admin/filestack_mgmt/Home.vue').default)

const app = new Vue({
    el: '#app',
    
});
