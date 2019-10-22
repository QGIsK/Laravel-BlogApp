/* eslint-disable */
// The Vue build version to load with the import command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from "vue";
import Axios from "axios";
import moment from "moment";
import Vuetify from "vuetify";
import wysiwyg from "vue-wysiwyg";
import SocialSharing from "vue-social-sharing";
import VueClipboards from "vue-clipboards";
// import interceptor from './helpers/httpInterceptor.js';
import "vuetify/dist/vuetify.min.css";
import App from "./App";
import store from "./store";

import router from "./router";

Vue.config.productionTip = false;

const token = localStorage.getItem("token");
Vue.use(SocialSharing);
Vue.use(VueClipboards);
Vue.use(Vuetify);
Vue.use(wysiwyg, {
    hideModules: { image: true }
});

Vue.filter("formatDate", function(value) {
    if (value) {
        return moment(String(value)).format("MM/DD/YYYY");
    }
});

Vue.filter("formatFullDate", function(value) {
    if (value) {
        return moment(String(value)).format("MM/DD/YYYY hh:mm");
    }
});

Vue.prototype.$http = Axios;

if (!process.env.NODE_ENV || process.env.NODE_ENV == "development") {
    Vue.prototype.$http.defaults.baseURL = "http://localhost:8000";
}

if (token) {
    Vue.prototype.$http.defaults.headers.common["Content-Type"] =
        "application/json";
    Vue.prototype.$http.defaults.headers.common["Authorization"] = token;
}

new Vue({
    el: "#app",
    router,
    store,
    components: {
        App
    },
    template: "<App/>"
});
