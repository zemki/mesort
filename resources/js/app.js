import "vue-material-design-icons/styles.css";
import Vue from "vue";
import Vuex, {mapState} from "vuex";
import store from "./store";
import moment from "moment";
import Alpine from "alpinejs";
import 'altcha';
import {ZiggyVue} from 'ziggy-js';
import {Ziggy} from "./ziggy";
import "./bootstrap";
import "./components";

window.Vue = Vue;

Vue.use(ZiggyVue, Ziggy);


Alpine.start();
window.Alpine = Alpine;


if (import.meta.env.VITE_ENV_MODE === "production") {
    window.Vue.config.devtools = false;
    window.Vue.config.debug = false;
    window.Vue.config.silent = true;
} else {
    window.Vue.config.devtools = true;
    window.Vue.config.debug = true;
    window.Vue.config.silent = false;
}

Vue.prototype.trans = (key) => {
    if (typeof window.trans[key] === "undefined") {
        return key;
    } else {
        if (window.trans[key] === "") return key;
        return window.trans[key];
    }
};


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuex);

Vue.use(moment);

Vue.mixin({
    data() {
        return {
            productionUrl:
                import.meta.env.VITE_ENV_MODE === "production" ? "/mesort" : "",
        };
    },
    computed: {
        url: function () {
            return document.URL.split("/").pop();
        },
    },
    methods: {

        copyObject: function (obj) {
            let objCopy = obj.slice();
            for (let i = 0; i < objCopy.length; i++) {
                let tempObj = Object.assign({}, objCopy[i]);
                objCopy[i] = tempObj; //replace the old obj with the new modified one.
            }

            return objCopy;
        },
        getCookie: function (cname) {
            let name = cname + "=";
            let ca = document.cookie.split(";");
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        setCookie: function (cname, cvalue, exdays) {
            let d = new Date();
            d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        },
        deleteCookie: function (name) {
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
        },
        goto: function (url) {
            window.location.href = url;
        },
    },

});

window.app = new Vue({
    el: "#app",
    store,
    computed: {
        ...mapState({
            interviewpagenames: (state) => state.newinterview.pagenames,
            interviewpage: (state) => state.newinterview.page,
            buttonnames: (state) => state.newinterview.buttonnames,
            sorting: (state) => state.newinterview.sorting,
            sortingtotal: (state) => state.newinterview.sortingtotal,
            presortQuestions: (state) => state.newinterview.presortQuestions,
            postsortQuestions: (state) => state.newinterview.postsortQuestions,
            sortingType: (state) => state.newinterview.sortingType,
            toggleCards: (state) => state.newinterview.toggleCards,
            selectedToken: (state) => state.newinterview.selectedToken,
            qsortSizeIndex: (state) => state.newinterview.qsortSizeIndex,
            qsortDistanceIndex: (state) =>
                state.newinterview.qsortDistanceIndex,
        }),
    },
    data: {
        dialog: {
            show: false,
            title: "",
            message: "",
            confirmText: "",
            onConfirm: null,
            onCancel: null,
        },
        moment: moment,
        newstudy: {},
        newemail: {
            valid_email: false,
            email: "",
            message: "",
        },
        users: {},
        newuser: {
            showmodal: false,
            edituser: 0,
            study: 0,
            activeTab: 0,
        }
    },
    events: {
        showmodalparent: function () {
            console.log("show modal parent");
        },
    },
    methods: {
        sendEmail() {
            let self = this;
            var re =
                /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (re.test(String(this.newemail.email).toLowerCase()))
                this.newemail.valid_email = true;
            else this.newemail.valid_email = false;

            if (!this.newemail.valid_email) {
                // add the class border-red-500 to the input with id newemail if it's not already there
                if (
                    !document
                        .getElementById("newemail")
                        .classList.contains("border-red-500")
                )
                    document
                        .getElementById("newemail")
                        .classList.add("border-red-500");

                return;
            }
            axios
                .post("changeemail", {email: self.newemail.email})
                .then((response) => {
                    // add the class border-red-500 to the input with id newemail if it's not already there
                    if (
                        document
                            .getElementById("newemail")
                            .classList.contains("border-red-500")
                    )
                        document
                            .getElementById("newemail")
                            .classList.remove("border-red-500");

                    self.newemail.message = response.data;
                    this.$forceUpdate();
                })
                .catch(function (error) {
                    self.newemail.message = error;
                });
        },
        toggleModalChangeEmail() {
            const body = document.querySelector("body");
            const modal = document.querySelector(".modal");
            modal.classList.toggle("opacity-0");
            modal.classList.toggle("pointer-events-none");
            body.classList.toggle("modal-active");

            this.newemail.email = "";
            this.newemail.message = "";
        },
        togglePersonModal() {
            this.$store.dispatch("togglePersonModal");
        },
        toggleCardsWindow: function () {
            store.commit("updateToggleCards", !this.toggleCards);
        },
        interviewconfirmpreviouspage: function () {
            this.$refs.thenewinterview.confirmpreviouspage();
        },
        interviewconfirmnextpage: function () {
            this.$refs.thenewinterview.confirmnextpage();
        },
        interviewaddsorting: function () {
            this.$refs.thenewinterview.addSorting();
        },
        interviewaddtoken: function () {
            store.commit("newtokenmodal");
        },
        catchOutsideClick(event, dropdown) {
            // When user clicks menu — do nothing
            if (dropdown == event.target) return false;

            // When user clicks outside of the menu — close the menu
            if (
                !dropdown.classList.contains("hidden") &&
                dropdown != event.target
            )
                return true;
        },
        showdropdown: function (id) {
            var self = this;

            const closeListerner = (e) => {
                if (self.catchOutsideClick(e, self.$refs.usermenu))
                    window.removeEventListener("click", closeListerner),
                        document.getElementById(id).classList.toggle("hidden");
            };

            window.addEventListener("click", closeListerner);
            document.getElementById(id).classList.toggle("hidden");
        },
        showmodal: function (id = null, study) {
            this.newuser.showmodal = !this.newuser.showmodal;
            this.newuser.edituser = id;
            this.newuser.study = study;

            // if(this.$refs.usertable) this.$refs.usertable.realodtable();
        },
        confirmgohome: function () {
            // Use native confirm dialog
            const shouldCancel = confirm(this.trans("Do you want to cancel this interview?"));

            if (shouldCancel) {
                for (let i = 1; i <= this.sortingtotal; i++) {
                    if (this.sortingType == 2) {
                        clearInterval(
                            this.$refs["thenewinterview"].$refs["sortingContainer" + i][0].interval
                        );
                        localStorage.setItem("tokens" + i, null);
                        localStorage.removeItem("tokens" + i);
                    }
                }
                window.location.href = "../";
            }
        },

        reloadusers: function (r) {
            this.users[r[1]] = r[0];
            this.$forceUpdate();
        },
        updateqsortSizeIndex: function (val) {
            let self = this;
            if (val === "plus" && self.qsortSizeIndex < 4)
                this.$store.commit(
                    "updateqsortSizeIndex",
                    self.qsortSizeIndex + 1
                );
            else if (val === "minus" && self.qsortSizeIndex > 0)
                this.$store.commit(
                    "updateqsortSizeIndex",
                    self.qsortSizeIndex - 1
                );
        },
        updateqsortDistanceIndex: function (val) {
            let self = this;
            if (val === "plus" && self.qsortDistanceIndex < 8)
                this.$store.commit(
                    "updateqsortDistanceIndex",
                    self.qsortDistanceIndex + 1
                );
            else if (val === "minus" && self.qsortDistanceIndex > 0)
                this.$store.commit(
                    "updateqsortDistanceIndex",
                    self.qsortDistanceIndex - 1
                );
        }
    },
});
