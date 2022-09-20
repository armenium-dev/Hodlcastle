
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

Vue.component('repeater-component', require('./components/RepeaterComponent.vue').default);
Vue.component('domain-repeater-component', require('./components/DomainRepeaterComponent.vue').default);
Vue.component('companies-groups-component', require('./components/CompaniesGroupsComponent.vue').default);
Vue.component('schedules-component', require('./components/SchedulesComponent.vue').default);
Vue.component('draggables', require('./components/DraggableComponent.vue').default);
Vue.component('quiz-questions-component', require('./components/QuizQuestionsComponent.vue').default);

const app = new Vue({
    el: '#app'
});
