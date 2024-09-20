import Vue from "vue";
import Vuex from "vuex";


Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        newinterview: {
            toggleCards: false,
            qsortSizeIndex: 3,
            qsortDistanceIndex: 2,
            center_x: 0,
            center_y: 0,
            bounds: {},
            parentPos: 0,
            imagepreset: [],
            newtoken: {},
            fetchtoken: false,
            uploadProgress: 0,
            page: 0,
            sorting: 1,
            sortingtotal: 1,
            pagenames: [
                "Pre-Sorting Questions",
                "Sorting",
                "Post-Sorting Questions",
            ],
            buttonnames: [
                ["", "Sorting >"],
                ["< Questions", "Questions >"],
                ["< Sorting", "End Interview"],
            ],
            loadedsortings: 0,
            newtokenmodal: false,
            selectedclassifier: {},
            presortQuestions: true,
            postsortQuestions: true,
            sortingType: 0,
            selectedToken: {},
        },
    },
    mutations: {
        newinterview(state, interview) {
            state.newinterview = interview;
        },
        setCenterAndBounds(state, payload) {
            state.newinterview.center_x = payload.center_x;
            state.newinterview.center_y = payload.center_y;
            state.newinterview.bounds = payload.bounds;
            state.newinterview.parentPos = payload.parentPos;
        },
        imagepreset(state, images) {
            state.newinterview.imagepreset = images;
        },
        settoken: function (state) {
            state.newinterview.newtoken.ispreset = false;
            state.newinterview.newtoken.image_path = "";
            let reader = new FileReader();
            let file = state.newinterview.newtoken.file;
            let self = this;
            reader.onload = function (file) {
                let im = new Image();
                im.onload = function () {
                    var canvas = document.createElement("canvas"),
                        ctx = canvas.getContext("2d");
                    canvas.width = 100;
                    canvas.height = 100;
                    if (im.width < 100 || im.height < 100) {
                        ctx.drawImage(
                            im,
                            0,
                            0,
                            100,
                            100,
                            0,
                            0,
                            canvas.width,
                            canvas.height
                        );
                    } else {
                        self.drawImageProp(
                            ctx,
                            im,
                            0,
                            0,
                            canvas.width,
                            canvas.height
                        );
                    }

                    let newimgUri = canvas.toDataURL("image/*").toString();

                    document.getElementById("newtoken").src = newimgUri;
                };
                im.src = file.target.result;

                // create a new property in
                // self.sorting.tokens[index] to save the base64
                // then save the file object in .file
                // to solve the error for the file object

                self.sorting.tokens[index].base64 = file.target.result;
            };
            reader.readAsDataURL(file);
            if (state.newinterview.newtoken.name === "") {
                state.newinterview.newtoken.name =
                    state.newinterview.newtoken.file.name;
            }
        },
        changeToken: function (state) {
            Vue.set(state, "fetchtoken", true);
        },
        setFetchToken: function (state, value) {
            Vue.set(state, "fetchtoken", value);
        },
        resetToken: function (state) {
            state.newinterview.newtoken.file.name = "";
        },
        updatepage: function (state, value) {
            state.newinterview.page = value;
        },
        updatesortingNumber: function (state, value) {
            state.newinterview.sorting = value;
        },
        updateselectedtoken: function (state, value) {
            state.newinterview.selectedToken = value;
        },
        updateselectedclassifier: function (state, value) {
            state.newinterview.selectedclassifier = value;
        },
        increasemaxsorting: function (state) {
            state.newinterview.sortingtotal++;
        },
        updateloadedsorting: function (state, value) {
            state.newinterview.loadedsortings = value;
        },
        newtokenmodal: function (state, value) {
            state.newinterview.newtokenmodal =
                !state.newinterview.newtokenmodal;
        },
        aretherepresortquestion: function (state, value) {
            state.newinterview.presortQuestions = value;
        },
        aretherepostsortquestions: function (state, value) {
            state.newinterview.postsortQuestions = value;
        },
        typeofsorting: function (state, value) {
            state.newinterview.sortingType = value;
        },
        updateToggleCards: function (state, value) {
            state.newinterview.toggleCards = value;
        },
        updateqsortSizeIndex: function (state, value) {
            state.newinterview.qsortSizeIndex = value;
        },
        updateqsortDistanceIndex: function (state, value) {
            state.newinterview.qsortDistanceIndex = value;
        },
    },
    actions: {
        async setimagepreset(context) {
            axios
                .post("../api/v1/getpresettokenimages")
                .then((response) => {
                    context.commit("imagepreset", response.data.presetimages);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async saveToken({ commit, state }, study) {
            axios
                .post("../api/v1/savetoken", {
                    token: state.newinterview.newtoken,
                    study: study,
                })
                .then((response) => {
                    commit("changeToken");
                })
                .catch((error) => {
                    console.log("error");
                    console.log(error);
                });
        },
        togglePersonModal({ commit, state }) {
            const body = document.querySelector("body");
            const modal = document.querySelector(
                ".modal" + state.newinterview.sorting
            );
            modal.classList.toggle("opacity-0");
            modal.classList.toggle("pointer-events-none");
            body.classList.toggle("modal-active");
            window.scrollTo(0, 0);
            document.body.scrollTop = 0;
        },
    },
    getters: {
        fetchtoken: (state) => state.newinterview.fetchtoken,
    },
});
