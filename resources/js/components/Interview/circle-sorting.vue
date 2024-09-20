<template>
    <section>
        <div :class="'sorting-container' + currentsorting" :id="'sortingc' + currentsorting" style="">
            <div class="flex w-auto h-auto">
                <h2
                    style="left: 5%; top: 15%"
                    class="absolute p-4 font-bold text-center text-white uppercase bg-blue-500 sm:text-xs md:text-xs lg:text-base md:mt-5 remove-from-screenshot"
                >
                    {{ trans("Choose from prepared tokens") }}
                </h2>

                <div
                    class="absolute z-20 flex h-auto sm:text-xs md:text-xs lg:text-base md:mt-10 token-container-created has-to-move"
                    id="token-container"
                    style="
                        top: 20%;
                        left: 5%;
                        flex-wrap: wrap;
                        width: 16.6666666667%;
                    "
                >
                    <div
                        class="z-30 sortingstart"
                        :class="'token' + currentsorting"
                        v-for="(t, index) in tokens"
                        :id="t.id"
                        v-if="t.author !== 0"
                        @click="assignclassifier(t.id, index)"
                        style=""
                    >
                        <div v-if="debug" class="debug-dot"></div>

                        <img
                            :src="t.image_path"
                            class="absolute top-0 left-0"
                            style="width: 50px; height: 50px; z-index: 100"
                            alt="token"
                        />

                        <img
                            v-for="(classifiersimages, cindex) in t.valutation
                                .classifiers"
                            :src="classifiersimages.dirname"
                            class="absolute classifierimage"
                            :style="
                                'margin-left:' +
                                (25 + (cindex + 1) * 27) +
                                'px;width: 25px;height: 25px;margin-top:12px;z-index:99;'
                            "
                            :alt="'classifier' + cindex"
                        />
                        <span
                            v-if="t.drag"
                            class="inline-flex overflow-visible items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800 token-label"
                            :id="'tokenlabel' + t.id"
                            v-html="t.name"
                            style="word-break: keep-all"
                        ></span
                        ><br/>

                        <div
                            style="width: 200px; top: 38px"
                            class="relative block rounded-none debugDiv"
                            v-show="debug"
                        >
                            <div
                                v-if="t.drag"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="'Circle ' + t.valutation.circle"
                            ></div>
                            <div
                                v-if="t.drag"
                                class="text-sm text-white bg-gray-800 rounded-none"
                                v-html="
                                    'Center Distance ' + t.valutation.distance
                                "
                            ></div>
                            <div
                                v-if="t.drag"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="'Position X ' + t.position.x"
                            ></div>
                            <div
                                v-if="t.drag"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="'Position Y ' + t.position.y"
                            ></div>
                            <div
                                v-if="t.drag && t.percentagePosition"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="
                                    '% position X ' + t.percentagePosition.x
                                "
                            ></div>
                            <div
                                v-if="t.drag && t.percentagePosition"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="
                                    '% position Y ' + t.percentagePosition.y
                                "
                            ></div>
                            <div
                                v-if="t.drag && t.valutation.sorting"
                                class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                v-html="'Sorting ' + t.valutation.sorting"
                            ></div>
                        </div>
                    </div>
                </div>
                <new-token ref="newtokenmodal" class="remove-from-screenshot">
                </new-token>
                <div class="flex w-auto h-auto">
                    <h2
                        style="right: 5%; top: 15%"
                        class="absolute p-4 font-bold text-center text-white uppercase bg-blue-500 sm:text-xs md:text-xs lg:text-base md:mt-5 remove-from-screenshot"
                    >
                        {{ trans("Your Created Tokens") }}
                    </h2>
                    <div
                        class="absolute z-20 flex h-auto sm:text-xs md:text-xs lg:text-base md:mt-8 token-container-created has-to-move"
                        style="
                            top: 20%;
                            right: 5%;
                            flex-wrap: wrap;
                            width: 16.6666666667%;
                        "
                    >
                        <div
                            class="inline-block sortingstart createdtokens"
                            :class="'token' + currentsorting"
                            v-for="(t, index) in tokens"
                            :id="t.id"
                            v-show="
                                t.author === 0 &&
                                t.valutation.sorting === currentsorting
                            "
                            @click="assignclassifier(t.id, index)"
                        >
                            <div v-if="debug" class="debug-dot"></div>
                            <div
                                v-if="t.isDragged"
                                @click="confirmdeletetoken(t)"
                                class="absolute z-40 font-bold text-center text-white bg-red-800 rounded-full cursor-pointer hover:bg-red-900 delete-token-button remove-from-screenshot"
                            >
                                x
                            </div>

                            <img
                                :src="t.image_path"
                                class="token-image"
                                alt="token image"
                            />
                            <img
                                v-for="(classifiersimages, cindex) in t
                                    .valutation.classifiers"
                                :src="classifiersimages.dirname"
                                class="absolute classifierimage"
                                :style="
                                    'margin-left:' +
                                    (25 + (cindex + 1) * 27) +
                                    'px;width: 25px;height: 25px;margin-top:12px;z-index:99;'
                                "
                                :alt="'classifier' + cindex"
                            />

                            <span
                                v-if="t.drag"
                                class="inline-flex items-center overflow-visible text-sm font-medium text-gray-800 bg-gray-100 rounded-md token-label"
                                :id="'tokenlabel' + t.id"
                                v-html="t.name"
                                style="word-break: keep-all"
                            ></span
                            ><br/>

                            <div
                                style="width: 200px; top: 38px"
                                class="relative block rounded-none debugDiv"
                                v-show="debug"
                            >
                                <div
                                    v-if="t.drag"
                                    class="block text-sm text-white bg-gray-800 rounded-none"
                                    v-html="'Circle ' + t.valutation.circle"
                                ></div>
                                <div
                                    v-if="t.drag"
                                    class="block text-sm text-white bg-gray-800 rounded-none"

                                    v-html="
                                        'Center Distance ' +
                                        t.valutation.distance
                                    "
                                ></div>
                                <div
                                    v-if="t.drag"
                                    class="block text-sm text-white bg-gray-800 rounded-none"
                                    v-html="'Position X ' + t.position.x"
                                ></div>
                                <div
                                    v-if="t.drag"
                                    class="block text-sm text-white bg-gray-800 rounded-none"
                                    v-html="'Position Y ' + t.position.y"
                                ></div>
                                <div
                                    v-if="t.drag && t.percentagePosition"
                                    class="block text-sm text-white bg-gray-800 rounded-none"
                                    v-html="
                                        '% position X ' + t.percentagePosition.x
                                    "
                                ></div>
                                <div
                                    v-if="t.drag && t.percentagePosition"
                                    class="block text-sm text-white bg-gray-800 rounded-none"
                                    v-html="
                                        '% position Y ' + t.percentagePosition.y
                                    "
                                ></div>
                                <div
                                    v-if="t.drag && t.valutation.sorting"
                                    class="block w-auto px-2 text-sm text-white bg-gray-800 rounded-none"
                                    v-html="'Sorting ' + t.valutation.sorting"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="center" v-show="debug"></div>
                <div id="currentToken" v-show="debug"></div>
            </div>
        </div>
        <!-- Snackbar component -->
        <snackbar :message="snackbarMessage" :duration="3000" v-if="showSnackbar"/>
        <custom-dialog v-if="dialog.show" :title="dialog.title" :message="dialog.message"
                       :confirm-text="dialog.confirmText" :on-confirm="dialog.onConfirm"
                       :on-cancel="dialog.onCancel"></custom-dialog>
    </section>

</template>

<script>
import interact from "interactjs";
import {mapState} from "vuex";
import Snackbar from "../snackbar.vue";
import CustomDialog from "../CustomDialogue.vue";

$(document).keydown(function (event) {
    if (
        event.ctrlKey == true &&
        (event.which == "61" ||
            event.which == "107" ||
            event.which == "173" ||
            event.which == "109" ||
            event.which == "187" ||
            event.which == "189")
    ) {
        event.preventDefault();
    }
    // 107 Num Key  +
    // 109 Num Key  -
    // 173 Min Key  hyphen/underscore key
    // 61 Plus key  +/= key
});

export default {
    props: ["availabletokens", "circles", "ntokens", "consult", "preset"],
    data() {
        return {
            newtoken: 0,
            tokens: [],
            newtoken: {
                name: "",
                file: null,
                path: "",
                ispreset: false,
            },
            drag: false,
            parentPos: "",
            tokenvalues: "",
            maxvalue: 100,
            minvalue: 0,
            bounds: {},
            center_x: 0,
            center_y: 0,
            export: false,
            debug: false,
            toastdebug: {},
            tokenDefaultValutation: {circle: 0, distance: 0},
            classifiermode: false,
            selectedclassifier: {},
            showSnackbar: false,
            snackbarMessage: "",
            dialog: {
                show: false,
                title: "",
                message: "",
                confirmText: "",
                onConfirm: null,
                onCancel: null
            },
        };
    },
    computed: {
        ...mapState({
            fetchtoken: (state) => state.fetchtoken,
            sortingtotal: (state) => state.newinterview.sortingtotal,
            currentsorting: (state) => state.newinterview.sorting,
            currentpage: (state) => state.newinterview.page,
        }),
    },
    watch: {
        currentpage: function (newVal, oldVal) {
            let self = this;
            this.$nextTick(() => {
                setTimeout(function () {
                    if (newVal === 1) {
                        self.getbounds();
                        self.setlabelposition();
                        self.setDebugDivPosition();
                    }
                }, 400);
            });
        },
        fetchtoken: function (newVal, OldVal) {
            if (newVal) {
                let urlParams = new URLSearchParams(window.location.search);
                let study_id = urlParams.get("study");
                axios
                    .post("../api/v1/gettokens", {study: study_id})
                    .then((response) => {
                        this.tokens.push(response.data);
                        this.tokens[this.tokens.length - 1].valutation = {
                            circle: 0,
                            distance: 0,
                            sorting: this.currentsorting,
                            classifiers: [],
                        };
                        this.tokens[this.tokens.length - 1].position = 0;
                        this.tokens[this.tokens.length - 1].drag = true;

                        this.showSnackbarMessage("Token was successfully added.", "success");
                        this.$refs.newtokenmodal.isComponentModalActive = false;
                        this.$store.state.fetchtoken = false;

                        let self = this;
                        setTimeout(function () {
                            self.setlabelposition();
                        }, 50);
                    })
                    .catch((error) => {
                    });
            }
        },
    },
    mounted() {
        var self = this;

        self.tokens = {};

        window.addEventListener("keydown", (e) => {
            if (e.keyCode == 66 && e.ctrlKey) {
                self.debugMode();
            }
        });

        self.createtokens();

        this.$nextTick(() => {
            setTimeout(function () {
                self.getbounds();
                self.setlabelposition();
                self.setDebugDivPosition();
            }, 400);
        });

        this.draggable();

        this.$store.commit("updateloadedsorting", this.sortingtotal);
    },
    methods: {
        resetSelectedClassifier: function () {
            var x = document.getElementById("classifiercontainer").querySelectorAll("img");
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("border-4");
                x[i].classList.remove("p-1");
                x[i].classList.remove("border");
                x[i].classList.remove("border-red-500");
            }
            this.$forceUpdate();
            this.classifiermode = false;
            this.$store.commit("updateselectedclassifier", {});
        },
        assignclassifier: function (tokenid, index) {
            if (this.classifiermode) {
                let classifierindex = this.tokens[index].valutation.classifiers.findIndex(
                    classifier => classifier === this.$store.state.newinterview.selectedclassifier
                );
                let classifierWasAlreadyAssigned = classifierindex != -1;
                if (classifierWasAlreadyAssigned) {
                    this.tokens[index].valutation.classifiers.splice(classifierindex, 1);
                } else {
                    this.tokens[index].valutation.classifiers.push(this.$store.state.newinterview.selectedclassifier);
                }
                this.resetSelectedClassifier();
            }
        },
        confirmdeletetoken: function (token) {
            this.dialog.show = true;
            this.dialog.title = "Delete Token";
            this.dialog.message = "Are you sure you want to delete this token?";
            this.dialog.confirmText = "Delete";
            this.dialog.onConfirm = () => {
                this.deletetoken(token);
                this.dialog.show = false;
            };
            this.dialog.onCancel = () => {
                this.dialog.show = false;
            };
        },
        deletetoken: function (token) {
            let self = this;
            axios.post("../api/v1/deletetoken", {
                study: this.tokens[0].pivot.study_id,
                token: token,
            }).then((response) => {
                let IndexTokenToDelete = this.tokens.findIndex(function (currentObject) {
                    return currentObject.id === token.id;
                });
                this.tokens[IndexTokenToDelete].export = false;
                document.getElementById(token.id).style.opacity = "0.0";
                this.showSnackbarMessage("Token was successfully deleted.", "success");
                setTimeout(function () {
                    self.$forceUpdate();
                }, 50);
            }).catch((error) => {
                this.showSnackbarMessage("Error deleting token.", "danger");
            });
        },
        drawDebugLine(token) {
            if (!this.debug) {
                const debugLines = document.querySelectorAll('[id^="debug-line-"]');
                debugLines.forEach(line => line.remove());
                return;
            }
            // Remove previous debug lines
            const existingLine = document.getElementById(`debug-line-${token.id}`);
            if (existingLine) {
                existingLine.remove();
            }

            // Get the largest circle (outermost) for reference
            const mainCircle = document.querySelector(`.round-sorting${this.circles}`);
            if (!mainCircle) {
                console.error("Main sorting circle not found!");
                return;
            }

            const mainCircleBounds = mainCircle.getBoundingClientRect();
            const centerX = mainCircleBounds.left + mainCircleBounds.width / 2;
            const centerY = mainCircleBounds.top + mainCircleBounds.height / 2;

            // Create a new debug line
            const line = document.createElement("div");
            line.setAttribute("id", `debug-line-${token.id}`);
            line.style.position = "absolute";
            line.style.width = "2px";
            line.style.backgroundColor = "blue";
            line.style.zIndex = "1000";

            // Token position
            const tokenX = token.position.x;
            const tokenY = token.position.y;

            const deltaX = tokenX - centerX;
            const deltaY = tokenY - centerY;
            const length = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
            const angle = Math.atan2(deltaY, deltaX) * 180 / Math.PI;

            line.style.height = length + "px";
            line.style.left = centerX + "px";
            line.style.top = centerY + "px";
            line.style.transformOrigin = "top left";
            line.style.transform = `rotate(${angle}deg)`;

            document.body.appendChild(line);
        },

        // Reuse the method for calculating token values but add a visual line on token drag end
        calculateTokenValues: function (event, self) {
            let currentTokenId = event.target.id;
            let sortingInstructionPosition = document
                .querySelector(".sorting-instructions")
                .getBoundingClientRect();

            let elementNewPosition = self.getElementGlobalCoordinates(
                    event.target
                ),
                currentX = elementNewPosition.left,
                currentY =
                    elementNewPosition.top + sortingInstructionPosition.height;

            let position =
                self.getTokenCircleAndCenterDistance(elementNewPosition);
            let tokenArrayIndex = self.tokens.findIndex(function (o) {
                return o.id == currentTokenId;
            });

            let percentagePositionx = (currentX / window.innerWidth) * 100;
            let percentagePositiony = (currentY / window.innerHeight) * 100;


            // Draw debug line after token is dragged
            self.drawDebugLine({
                id: currentTokenId,
                position: {
                    x: currentX,
                    y: currentY,
                }
            });

            return {
                currentX,
                currentY,
                position,
                tokenArrayIndex,
                percentagePositionx,
                percentagePositiony,
            };
        },
        draggable: function () {
            var self = this;

            interact(".token" + this.sortingtotal).draggable({
                inertia: false,
                restrict: {
                    restriction: ".sorting-container",
                    endOnly: true,
                    elementRect: {top: 0, left: 1, bottom: 1, right: 1},
                },
                autoScroll: false,
                onmove: this.dragMoveListener,
                onend: function (event) {

                    if (!self.classifiermode) {

                        let {
                            currentX,
                            currentY,
                            position,
                            tokenArrayIndex,
                            percentagePositionx,
                            percentagePositiony,
                        } = self.calculateTokenValues(event, self);

                        self.tokens[tokenArrayIndex].valutation.circle =
                            self.circles - position.circle;
                        self.tokens[tokenArrayIndex].valutation.distance =
                            position.distance.toFixed(3);
                        self.tokens[tokenArrayIndex].position = {
                            x: currentX,
                            y: currentY,
                        };
                        self.tokens[tokenArrayIndex].percentagePosition = {
                            x: percentagePositionx.toFixed(3),
                            y: percentagePositiony.toFixed(3),
                        };

                        self.tokens.forEach(function (token) {
                            token.isDragged = false;
                        });
                        self.tokens[tokenArrayIndex].isDragged = true;

                        self.$forceUpdate();
                    }
                },
            });
        },
        moveToken: function (event) {
            this.drag = true;
            var target = event.target,
                x = (parseFloat(target.getAttribute("data-x")) || 0) + event.dx,
                y = (parseFloat(target.getAttribute("data-y")) || 0) + event.dy;

            target.style.webkitTransform = target.style.transform = "translate(" + x + "px, " + y + "px)";
            target.setAttribute("data-x", x);
            target.setAttribute("data-y", y);
        },
        dragMoveListener: function (event) {
            if (!this.classifiermode) {
                this.moveToken(event);
            }
        },
        getTokenCircleAndCenterDistance: function (position) {
            let circle = 0;
            let distance = 0;

            // MATH -- FIND IF A POINT BELONGS INSIDE A CIRCLE
            // (x - center_x)^2 + (y - center_y)^2 < radius^2
            // add half token size to distance calculations

            for (let i = 0; i < this.circles; i++) {
                //
                if (
                    Math.pow(position.left + 25 - this.center_x, 2) +
                    Math.pow(position.top + 25 - this.center_y, 2) <
                    Math.pow(
                        (this.center_x - this.bounds.left) *
                        ((this.circles - i) / this.circles),
                        2
                    )
                ) {
                    circle = i;
                } else if (
                    Math.pow(position.left + 25 - this.center_x, 2) +
                    Math.pow(position.top + 25 - this.center_y, 2) >=
                    Math.pow(this.center_x - this.bounds.left, 2)
                ) {
                    circle = this.circles;
                }
            }

            distance = Math.hypot(
                position.top + 25 - this.center_y,
                position.left + 25 - this.center_x
            );

            return {circle: circle, distance: distance};
        },
        debugMode: function () {
            this.debug = !this.debug;
            if (this.debug) {
                this.showSnackbarMessage("You are in debug mode, press CTRL+b to exit.", "danger");
            } else {
                // Clean up debug lines when exiting debug mode
                const lines = document.querySelectorAll('[id^=debug-line]');
                lines.forEach(line => line.remove());
            }
        },

        getbounds: function () {
            const sortingContainer = document.querySelector(".sorting-container" + this.currentsorting);
            const roundSorting = document.querySelector(".round-sorting" + this.circles);

            if (!sortingContainer || !roundSorting) {
                console.error("Sorting container or round sorting element is not found.");
                return;
            }

            this.parentPos = sortingContainer.getBoundingClientRect();
            const globalCoords = this.getElementGlobalCoordinates(roundSorting);
            this.bounds = {
                top: globalCoords.top,
                left: globalCoords.left,
                right: globalCoords.right,
                bottom: globalCoords.bottom
            };

            var self = this;
            setTimeout(function () {
                self.calculateCircleCenter();
                var d = document.getElementById("center");
                d.style.position = "absolute";
                d.style.backgroundColor = "red";
                d.style.width = "4px";
                d.style.height = "4px";
                d.style.left = self.center_x - 2 + "px";
                d.style.top = self.center_y - 2 + "px";
            }, 100);
        },
        calculateCircleCenter: function () {
            this.center_x =
                this.bounds.left +
                document.querySelector(".round-sorting" + this.circles)
                    .offsetWidth /
                2;
            this.center_y =
                document.querySelector(".round-sorting" + this.circles)
                    .offsetHeight /
                2 +
                this.bounds.top;
        },

        getElementGlobalCoordinates: function (token) {
            if (!token) {
                console.error("Token element is null");
                return {top: 0, left: 0, right: 0, bottom: 0, x: 0, y: 0};
            }

            const childrenPos = token.getBoundingClientRect();
            return {
                top: childrenPos.top - this.parentPos.top,
                right: childrenPos.right - this.parentPos.right,
                bottom: childrenPos.bottom - this.parentPos.bottom,
                left: childrenPos.left - this.parentPos.left,
                x: childrenPos.x,
                y: childrenPos.y
            };
        },
        createtokens: function () {
            this.tokens = this.availabletokens;
            for (var i = 0; i < this.tokens.length; i++) {
                let tokensorting = this.tokens[i].author == 0 ? 0 : this.sortingtotal;
                this.tokens[i].valutation = {circle: 0, distance: 0, sorting: tokensorting, classifiers: []};
                this.tokens[i].position = 0;
                this.tokens[i].drag = true;
            }
        },
        setlabelposition: function () {
            var labelDiv = document.getElementsByClassName("token-label");
            for (var i = 0; i < labelDiv.length; i++) {
                var label = labelDiv.item(i);
                if (label.offsetWidth > 50) {
                    label.style.left = "-" + (label.offsetWidth - 50) / 2 + "px";
                } else {
                    label.style.left = "0px";
                }
            }
        },
        setDebugDivPosition: function () {
            var debugDiv = document.getElementsByClassName("debugDiv");
            for (var i = 0; i < debugDiv.length; i++) {
                debugDiv.item(i).style.left = "-75px";
            }
        },
        showSnackbarMessage: function (message, type) {
            this.snackbarMessage = message;
            this.showSnackbar = true;
            setTimeout(() => {
                this.showSnackbar = false;
            }, 3000);
        },
    },
    components: {
        Snackbar,
        CustomDialog,
    },
};
</script>
<style scoped>
.debug-dot {
    background-color: red;
    height: 4px;
    z-index: 101;
    width: 4px;
    position: absolute;
    top: 25px;
    left: 25px;
}

.token-label {
    top: 50px;
    position: absolute;
    width: auto;
    height: 17px;
    border-radius: 0;
    display: inline-block;
}

.token-image {
    width: 50px;
    height: 50px;
    position: absolute;
    z-index: 30;
    top: 0;
    left: 0;
    -webkit-user-select: none;
}

.delete-token-button {
    margin: -20px 0px 0px 30px;
    width: 1.5rem;
    height: 1.5rem;
}

.token-container-created > div {
    width: 50px;
    height: 50px;
    position: relative;
    top: 0;
    right: 0;
    -webkit-user-select: none;
}

.token-container-created {
    position: absolute;
    width: 100%;
    right: 0;
    -webkit-user-select: none;
}

.token-container {
    position: absolute;
}

.debug-line {
    pointer-events: none;
    background-color: blue;
    z-index: 10000;
}

</style>
