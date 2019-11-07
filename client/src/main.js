/* eslint-disable */
import Vue from "vue";
import Axios from "axios";
import moment from "moment";
import Vuetify from "vuetify";
import wysiwyg from "vue-wysiwyg";
import "vuetify/dist/vuetify.min.css";
import App from "./App";
import store from "./store";

import router from "./router";

Vue.config.productionTip = false;

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
    Vue.prototype.$http.defaults.baseURL = "http://127.0.0.1:8000";
}

const token = localStorage.getItem("token");

Vue.prototype.$http.defaults.headers.common["Content-Type"] =
    "application/json";

if (token) {
    Vue.prototype.$http.defaults.headers.common[
        "Authorization"
    ] = `Bearer ${token}`;
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
