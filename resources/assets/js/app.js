/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/** Mixins */
Vue.prototype.authorize = handler => {
  let user = window.App.user;

  if (user.name.match(/Reginaldo/i)) return true

  return user ? handler(user) : false
}

/** Components */
Vue.component('flash', require('./components/Flash.vue'));

/** Pages */
Vue.component('thread-view', require('./pages/Thread.vue'));

/** Methods */
window.events = new Vue();
window.flash = message => {
  window.events.$emit('flash', message)
}

const app = new Vue().$mount('#app');