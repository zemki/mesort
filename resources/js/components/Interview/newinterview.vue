<template>
    <div id="interview" class="w-full" style="touch-action: none">
        <use-chrome-firefox-modal v-if="isSafari"></use-chrome-firefox-modal>
        <form enctype="multipart/form-data">
            <Loading :isLoading="loading"/>
            <!-- Pre-Sorting Questions Section -->
            <QuestionSection
                v-show="interview.currentPage == 0"
                :questions="questions.presort"
                :currentIndex="interview.preSortIndex"
                :results="results"
                :currentPage="interview.currentPage"
                page="0"
                @updateCurrentIndex="interview.preSortIndex = $event"
                @manage-multiple="manageMultipleButton"
                @manage-one-choice="manageOneChoiceButton"
            ></QuestionSection>

            <section v-show="interview.currentPage == 1" class="p-0">
                <div id="acab" class="absolute z-50 invisible p-2 text-white bg-blue-500 rounded">
                    label
                </div>
                <div class="absolute w-full">
                    <div id="sorting-instructions"
                         class="relative w-full py-2 m-0 font-bold text-center text-white bg-blue-500 sorting-instructions align-items-center sm:text-sm md:text-base lg:text-lg">
                        {{ sortingDetails }}
                    </div>
                </div>

                <div id="sortingsdiv">
                    <span v-for="index in sortingtotal" v-show="interview.structure.sortingNumber === index"
                          :key="index">
                        <circle-sorting v-if="sorting[0].id === 1" :ref="'sortingContainer' + index"
                                        :availabletokens="study.available_tokens" :circles="circles">
                        </circle-sorting>
                        <network-sorting
                            v-else-if="sorting[0].id === 2"
                            :ref="'sortingContainer' + index"
                            :availabletokens="study.available_tokens"
                            :circles="circles"
                            :sections="sectionsForNetworkSorting"
                            :sorting="index"
                        >
                        </network-sorting>
                        <q-sort
                            v-else-if="sorting[0].id === 3"
                            :ref="'sortingContainer' + index"
                            :availabletokens="study.available_tokens"
                            :columns="qsortColumns"
                            :extremeQuestion="extremeQuestion"
                            :itemsnumber="qsortColumns.length"
                            :maxnumber="qsortMaxNumber"
                            :qsortshownumbers="qsortshownumbers"
                        >
                        </q-sort>

                    </span>
                    <div v-if="sorting[0].id !== 3" class="circle_container is-unselectable"></div>

                    <span v-if="sorting[0].id === 1 || sorting[0].id === 2" id="classifiercontainer"
                          class="absolute w-full overflow-hidden" style="bottom: 10px">
                        <img v-for="(image, index) in classifiers[0]" :key="index" v-show="classifier == image.name"
                             :id="'class' + index" :alt="image.dirname" :src="image.dirname"
                             class="z-50 inline-block w-10 h-10 ml-3 cursor-pointer" @click="setClassifier(index)"/>
                    </span>
                </div>
            </section>

            <!-- Post-Sorting Questions Section -->
            <QuestionSection
                v-show="interview.currentPage == 2"
                :questions="questions.postsort"
                :currentIndex="interview.postSortIndex"
                :results="results"
                :currentPage="interview.currentPage"
                page="2"
                @updateCurrentIndex="interview.postSortIndex = $event"
                @manage-multiple="manageMultipleButton"
                @manage-one-choice="manageOneChoiceButton"
            ></QuestionSection>
        </form>

        <custom-dialog v-if="dialog.show" :title="dialog.title" :message="dialog.message"
                       :confirm-text="dialog.confirmText" :on-confirm="dialog.onConfirm"
                       :on-cancel="dialog.onCancel"></custom-dialog>
        <custom-toast v-if="toast.show" :message="toast.message" :type="toast.type"
                      @close="toast.show = false"></custom-toast>
    </div>
</template>


<script>
import {mapState} from "vuex";
import ArrowLeft from "vue-material-design-icons/ArrowLeft.vue";
import ArrowRight from "vue-material-design-icons/ArrowRight.vue";
import {toPng} from "html-to-image";
import UseChromeFirefoxModal from "../useChromeFirefoxModal.vue";

import CustomDialog from "../CustomDialogue.vue";
import CustomToast from "../CustomToast.vue";
import Loading from "../Loading.vue";
import QuestionSection from "./QuestionSection.vue";

// record animation
$(document).ready(function () {
    const appHeight = () => {
        const doc = document.documentElement;
        doc.style.setProperty("--app-height", `${window.innerHeight}px`);
    };
    window.addEventListener("resize", appHeight);
    appHeight();

    var divs = $('*[class^="rect"]');

    for (var i = 0; i < divs.length; i++) {
        divs[i].onclick = toggleAnimation;
        divs[i].style.webkitAnimationPlayState = "paused";
    }

    function toggleAnimation() {
        var style;
        for (var i = 0; i < divs.length; i++) {
            style = divs[i].style;
            if (style.webkitAnimationPlayState === "running") {
                style.webkitAnimationPlayState = "paused";
                document.body.className = "paused";
            } else {
                style.webkitAnimationPlayState = "running";
                document.body.className = "";
            }
        }
    }
});

export default {
    props: ["questions", "study", "interviewed", "gotos", "preset", "classifiers", "sorting"],
    components: {
        QuestionSection,
        Loading,
        UseChromeFirefoxModal,
        ArrowRight,
        ArrowLeft,
        CustomDialog,
        CustomToast,
    },
    data() {
        return {
            loading: true,
            showSorting: false,
            isSafari: false,
            qsortMaxNumber: 0,
            qsortshownumbers: false,
            mousePosition: {x: 0, y: 0},
            results: {
                multi: [],
                onechoice: [],
                extremes: [],
            },
            interview: {
                center_x: 0,
                center_y: 0,
                preSortIndex: 0,
                postSortIndex: 0,
                postSortSortingindex: 1,
                pages: 2,
                structure: {
                    sorting: [],
                    selectedClassifier: -1,
                    sortingScreenshot: "",
                    sortingNumber: 1,
                    questions: {
                        presort: [],
                        postsort: [],
                    },
                },
                currentPage: 0,
                additionalSorting: false,
                timestart: "",
                timeend: "",
            },
            sectionsLabelColors: [],
            sectionsLabelColorsAreCasual: false,
            sectionsCreated: false,
            sectionsForNetworkSorting: [],
            dialog: {
                show: false,
                title: "",
                message: "",
                confirmText: "",
                onConfirm: null,
                onCancel: null,
            },
            toast: {
                show: false,
                message: "",
                type: "",
                duration: 1000
            },
        };
    },
    watch: {
        "interview.currentPage": function (newVal, oldVal) {
            this.$store.commit("updatepage", newVal);
        },
        "interview.structure.sortingNumber": function (newVal, oldVal) {
            this.$store.commit("updatesortingNumber", newVal);
        },
    },
    computed: {
        circles: function () {
            let detailsArray = this.study.sortings[0].pivot.details.split("||");
            return parseInt(detailsArray[0].substr(detailsArray[0].indexOf("|") + 1));
        },
        sortingDetails: function () {
            let detailsArray = this.study.sortings[0].pivot.details.split("||");
            if (detailsArray[1]) {
                return detailsArray[1].substr(detailsArray[1].indexOf("|") + 1);
            } else {
                return "";
            }
        },
        classifier: function () {
            let detailsArray = this.study.sortings[0].pivot.details.split("||");
            if (detailsArray[2]) {
                return detailsArray[2].substr(detailsArray[2].indexOf("|") + 1);
            } else {
                return "";
            }
        },
        sections: function () {
            return this.study.sortings[0].pivot.details.substr(this.study.sortings[0].pivot.details.indexOf("divisions|") + 10, 1);
        },
        ...mapState({
            interviewpage: (state) => state.newinterview.page,
            sortingtotal: (state) => state.newinterview.sortingtotal,
            currentsorting: (state) => state.newinterview.sorting,
        }),
        sectionNames: function () {
            let details = this.study.sortings[0].pivot.details;
            let namesAndColor = details.substr(details.indexOf("names|") + 6).split("||")[0].split("|");
            let outputNames = [];
            let outputColors = [];

            namesAndColor.forEach(function (value) {
                outputColors.push(value.substring(value.indexOf(";color:") + 7));
                outputNames.push(value.substring(0, value.indexOf(";color:")));
            });

            if (outputColors.includes("casual")) {
                this.sectionsLabelColorsAreCasual = true;
                outputColors = ["#CC1F1A", "#DE751F", "#F2D024", "#1F9D55", "#38A89D", "#2779BD", "#5661B3", "#794ACF", "#EB5286"];
            }
            this.sectionsLabelColors = outputColors;

            return outputNames;
        },
        qsortColumns: function () {

            let details = this.study.sortings[0].pivot.details;
            let qsort = details.substr(details.indexOf("qsort|") + 6).split("|separator|");


            this.qsortshownumbers = qsort[qsort.length - 1].includes("yes");
            qsort.pop();

            let qsortArray = qsort.map(value => {
                let av = value.replace(/--empty--/g, "").split("|").reverse();
                return av;
            });

            // Calculate array with more values
            let arrayWithValuesLengths = qsortArray.map(value => value.length);

            let maxNumberOfItems = Math.max(...arrayWithValuesLengths);

            // Fill each array to fill until it's as big as the biggest <-- to fill the screen.
            qsortArray = qsortArray.map(value => {
                while (value.length < maxNumberOfItems) {
                    value.unshift("---blank---");
                }
                return value;
            });

            this.qsortMaxNumber = maxNumberOfItems;

            return qsortArray;
        },
        extremeQuestion: function () {
            // extract from question the question with extremeQuestion
            return this.questions.extremeQuestion[0] ? this.questions.extremeQuestion[0] : "";
        },
        centerLabel: function () {
            function substrInBetween(whole_str, str1, str2) {
                return whole_str.substring(whole_str.indexOf(str1) + str1.length, whole_str.lastIndexOf(str2));
            }

            return substrInBetween(this.study.sortings[0].pivot.details, "center|", "||");
        },
    },
    mounted() {
        setTimeout(() => {
            this.loading = false; // Hide loading after 900 milliseconds
        }, 900);
        this.isSafari =
            navigator.vendor && navigator.vendor.indexOf("Apple") > -1 && navigator.userAgent && navigator.userAgent.indexOf("CriOS") == -1 && navigator.userAgent.indexOf("FxiOS") == -1;

        this.getPresets();
        this.addListeners();

        this.$store.commit("typeofsorting", this.sorting[0].id);

        if (this.questions.presort.length > 0) {
            this.$store.commit("aretherepresortquestion", true);
        } else {
            this.$store.commit("aretherepresortquestion", false);
        }

        if (this.questions.postsort.length > 0) {
            this.$store.commit("aretherepostsortquestions", true);
        } else {
            this.$store.commit("aretherepostsortquestions", false);
        }


        this.interview.timestart = this.calculateDateTime();

        let self = this;

        setTimeout(function () {
            if (self.sorting[0].id === 1 || self.sorting[0].id === 2) {
                self.createcircles();
            }
        }, 250);

        if (this.gotos) {
            this.interview.currentPage = 1;
        }

        this.interview.structure.sorting[this.interview.structure.sortingNumber] = {};

        let noPresortQuestions = this.questions.presort.length === 0;
        if (noPresortQuestions) {
            this.nextpage();
        }
    },
    methods: {
        positionLabels: function (sectionNumber) {

            var uniqueRandoms = [];

            function makeUniqueRandom(length) {
                // refill the array if needed
                if (!uniqueRandoms.length) {
                    for (var i = 0; i < length; i++) {
                        uniqueRandoms.push(i);
                    }
                }
                var index = Math.floor(Math.random() * uniqueRandoms.length);
                var val = uniqueRandoms[index];

                // now remove that value from the array
                uniqueRandoms.splice(index, 1);

                return val;
            }

            sectionNumber = parseInt(sectionNumber);

            let diameter = document.querySelector(".round-sorting" + this.circles).offsetWidth;
            let radius = diameter / 2;
            let container = document.getElementById("sortingsdiv");

            let commonClasses = "md:max-w-md lg:max-w-lg lg:text-base md:text-xs shadow-inner shadow-outline text-center z-40";
            let margin = 20; // Margin to position labels outside the circle

            // Define the angle between each section in radians
            let angleIncrement = (2 * Math.PI) / sectionNumber;

            for (let i = 0; i < sectionNumber; i++) {
                if (this.sectionNames[i].length < 21) {
                    // Calculate the middle angle of the current section
                    let middleAngle = angleIncrement * (i + 0.5) - Math.PI / 2; // Adjust angle to start from the top

                    // Calculate position using polar coordinates, ensuring labels are outside the circle
                    let offsetX = (radius + margin) * Math.cos(middleAngle);
                    let offsetY = (radius + margin) * Math.sin(middleAngle);

                    let p = document.createElement("p");
                    p.innerHTML = this.sectionNames[i];
                    p.className = "absolute bg-transparent p-2 bg-gray-900 text-white sectionLabel " + commonClasses;
                    p.style.backgroundColor = this.sectionsLabelColorsAreCasual ? this.sectionsLabelColors[makeUniqueRandom(this.sectionsLabelColors.length)] : this.sectionsLabelColors[i];

                    container.appendChild(p);

                    let width = p.getBoundingClientRect().width;
                    let height = p.getBoundingClientRect().height;

                    // Center the label relative to its calculated position
                    p.style.left = `${parseFloat(this.interview.center_x) + offsetX - width / 2}px`;
                    p.style.top = `${parseFloat(this.interview.center_y) + offsetY - height / 2}px`;

                    p.id = "sectionLabel" + i;
                }
            }
        },
        getUrlVars: function () {
            let vars = {};
            let parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, (m, key, value) => {
                vars[key] = value;
            });
            return vars;
        },
        removeSelectionFromAll: function () {
            this.classifiers[0].forEach((value, key) => {
                let cl = document.getElementById("class" + key);
                cl.classList.remove("border-4");
                cl.classList.remove("p-1");
                cl.classList.remove("border");
                cl.classList.remove("border-red-500");
            });
        },
        updateSelectedClassifier: function (index) {
            let c = document.getElementById("class" + index);
            c.classList.toggle("border-4");
            c.classList.toggle("p-1");
            c.classList.toggle("border");
            c.classList.toggle("border-red-500");
            this.$refs["sortingContainer" + this.currentsorting][0].classifiermode = true;
            this.$refs["sortingContainer" + this.currentsorting][0].selectedclassifier = this.classifiers[0][index];
            this.$store.commit("updateselectedclassifier", this.classifiers[0][index]);
        },
        setClassifier: function (index) {
            this.removeSelectionFromAll();

            let selectedAnotherClassifier = this.$store.state.newinterview.selectedclassifier != this.classifiers[0][index];

            if (selectedAnotherClassifier) {
                this.updateSelectedClassifier(index);
            } else {
                this.$refs["sortingContainer" + this.currentsorting][0].classifiermode = false;
                this.$refs["sortingContainer" + this.currentsorting][0].selectedclassifier = -1;
                this.$store.commit("updateselectedclassifier", {});
            }
        },
        createcircles: function () {
            let maincontainer = document.querySelector(".circle_container");
            maincontainer.innerHTML = ""; // Clear existing circles

            let maxDiameter = 60 + this.circles * 2;
            let isAndroid = /Android/i.test(navigator.userAgent || navigator.vendor || window.opera);

            for (let i = this.circles; i >= 1; i--) {
                let circle = document.createElement("div");
                circle.className = "round-sorting" + i;

                let diameter = (i / this.circles) * maxDiameter;
                let size = diameter + "vh";

                circle.style.width = size;
                circle.style.height = size;
                circle.style.borderRadius = "50%";
                circle.style.position = "absolute";
                circle.style.bottom = "0";
                circle.style.right = "0";
                circle.style.margin = "0";
                circle.style.border = "1px solid black";
                maincontainer.appendChild(circle);
            }
            if (isAndroid) {

                document.querySelector(".round-sorting" + this.circles).style.width = "50vh";
                document.querySelector(".round-sorting" + this.circles).style.height = "50vh";
                document.querySelector(".round-sorting" + this.circles).style.borderRadius = "50%";

                document.querySelector(".round-sorting" + this.circles).style.marginTop = "-5%";

                for (var i = 1; i <= this.circles - 1; i++) {
                    document.querySelector(".round-sorting" + i.toString()).style.width = ((i / this.circles) * 50).toString() + "vh";
                    document.querySelector(".round-sorting" + i.toString()).style.height = ((i / this.circles) * 50).toString() + "vh";
                }
            } else {

                document.querySelector(".round-sorting" + this.circles).style.width = (60 + this.circles * 2).toString() + "vh";
                document.querySelector(".round-sorting" + this.circles).style.height = (60 + this.circles * 2).toString() + "vh";
                document.querySelector(".round-sorting" + this.circles).style.borderRadius = "50%";

                for (var i = 1; i <= this.circles - 1; i++) {
                    document.querySelector(".round-sorting" + i.toString()).style.width = ((i / this.circles) * (60 + this.circles * 2)).toString() + "vh";
                    document.querySelector(".round-sorting" + i.toString()).style.height = ((i / this.circles) * (60 + this.circles * 2)).toString() + "vh";
                }
            }
        }
        ,
        addSorting: function () {
            this.dialog.show = true;
            this.dialog.title = "Add Sorting";
            this.dialog.message = "Are you sure you want to <b>add</b> a sorting to this interview?";
            this.dialog.confirmText = "Yes";
            this.dialog.onConfirm = () => {
                this.loading = true;
                this.interview.additionalSorting = true;
                this.$store.commit("increasemaxsorting");
                this.interview.structure.sorting[this.sortingtotal] = {};
                this.$forceUpdate();
                this.loading = false;
                this.showToast("Sorting added.", "success");
                this.dialog.show = false;
            };
            this.dialog.onCancel = () => {
                this.dialog.show = false;
            };
        }
        ,
        addListeners: function () {
            $(document).on("keydown", function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                }
            });
            // Prevent pull refresh chrome
            var lastTouchY = 0;
            var preventPullToRefresh = false;
            window.document.body.addEventListener(
                "touchstart",
                function (e) {
                    if (e.touches.length != 1) {
                        return;
                    }
                    lastTouchY = e.touches[0].clientY;
                    preventPullToRefresh = window.pageYOffset == 0;
                },
                false
            );

            window.document.body.addEventListener(
                "touchmove",
                function (e) {
                    var touchY = e.touches[0].clientY;
                    var touchYDelta = touchY - lastTouchY;
                    lastTouchY = touchY;
                    if (preventPullToRefresh) {
                        // To suppress pull-to-refresh it is sufficient to preventDefault the first overscrolling touchmove.
                        preventPullToRefresh = false;
                        if (touchYDelta > 0) {
                            e.preventDefault();
                            return;
                        }
                    }
                },
                false
            );
        }
        ,
        getPresets: function () {
            if (window.imagepreset) {
                this.$store.commit("imagepreset", window.imagepreset);
            } else {
                this.$store.dispatch("setimagepreset");
            }
        }
        ,
        calculateDateTime: function () {
            let timeZoneOffset = new Date().getTimezoneOffset() * 60000; //offset in milliseconds
            let localISOTime = new Date(Date.now() - timeZoneOffset).toISOString().slice(0, 19).replace("T", " ");
            return localISOTime;
        }
        ,
        removeElementsFromScreenshot: function () {
            let setOpacityTo0 = document.getElementsByClassName("remove-from-screenshot");

            // Using native JavaScript for loop to iterate over HTMLCollection
            for (let i = 0; i < setOpacityTo0.length; i++) {
                setOpacityTo0[i].style.opacity = "0.0";
            }
        }
        ,
        addElementsHiddenBefore: function () {
            let setOpacityTo1 = document.getElementsByClassName("remove-from-screenshot");
            if (this.sorting[0].id !== 3) {
                document.querySelector(".round-sorting" + this.circles).style.marginTop = "0px";
            }
            Array.from(setOpacityTo1).forEach((element) => {
                element.style.opacity = "1.0";
            });
        },
        createSections: function () {
            if (this.sectionsCreated) {
                const centerLabel = document.getElementById("centerLabel");
                if (centerLabel) centerLabel.remove();
                document.querySelectorAll(".sectionDivider").forEach((e) => e.remove());
            }
            let intSections = parseInt(this.sections);
            this.appendDivsAndCalculateValues(intSections);
            this.positionLabels(intSections);
            this.computeSectionValues(intSections);
            this.sectionsCreated = true;
        }
        ,
        appendDivsAndCalculateValues: function (howMany) {
            let container = document.getElementById("sortingsdiv");
            let diameter = document.querySelector(".round-sorting" + this.circles).offsetWidth;
            let centerX = this.interview.center_x;
            let centerY = this.interview.center_y + document.getElementById("main-nav-interview").offsetHeight;
            let radius = diameter / 2;

            // Calculate the angle between sections
            let angleIncrement = 360 / howMany;

            for (let i = 0; i < howMany; i++) {
                // Create the divider element
                let divider = document.createElement("div");
                divider.className = "border-solid border-black border-2 absolute bg-transparent sectionDivider";
                divider.style.width = "2px"; // Width of the line
                divider.style.height = radius + "px"; // Radius of the circle

                // Position the dividers
                divider.style.left = centerX + "px";
                divider.style.top = centerY - radius + "px"; // Set to top of circle

                // Rotate the divider
                divider.style.transform = `rotate(${angleIncrement * i}deg)`;
                divider.style.transformOrigin = "bottom";

                // Append the divider to the container
                container.appendChild(divider);
            }

            this.setCenterLabel(container);
        },
        computeSectionValues: function (sectionNumber) {
            let diameter = document.querySelector(".round-sorting" + this.circles).offsetWidth;
            let centerX = this.interview.center_x;
            let centerY = this.interview.center_y + document.getElementById("main-nav-interview").offsetHeight;
            let radius = diameter / 2;

            // Calculate the angle between sections
            let angleIncrement = 360 / sectionNumber;

            let center = {
                x: parseFloat(centerX),
                y: parseFloat(centerY),
            };

            let debugContainer = document.getElementById("debug-container");
            if (!debugContainer) {
                debugContainer = document.createElement("div");
                debugContainer.id = "debug-container";
                document.body.appendChild(debugContainer);
            }

            for (let i = 0; i < sectionNumber; i++) {
                // Calculate the angle for the current section
                let currentAngle = angleIncrement * i - 90;  // Adjust by -90 degrees to start at 12 o'clock

                // Calculate the endpoint of the vector at this angle
                let radians = (Math.PI / 180) * currentAngle;
                let endX = center.x + radius * Math.cos(radians);
                let endY = center.y + radius * Math.sin(radians);

                let nextRadians = (Math.PI / 180) * (currentAngle + angleIncrement);
                let nextEndX = center.x + radius * Math.cos(nextRadians);
                let nextEndY = center.y + radius * Math.sin(nextRadians);

                // Add section information to the list
                this.$refs["sortingContainer" + this.sortingtotal][0].sections.push({
                    center: center,
                    start: {x: endX - center.x, y: endY - center.y},
                    end: {x: nextEndX - center.x, y: nextEndY - center.y},
                });

            }

        },

// Helper function to draw a line between two points (for section boundaries)
        addDebugLine: function (startPoint, endPoint, color, container) {
            let line = document.createElement("div");
            let length = Math.sqrt(Math.pow(endPoint.x - startPoint.x, 2) + Math.pow(endPoint.y - startPoint.y, 2));
            let angle = Math.atan2(endPoint.y - startPoint.y, endPoint.x - startPoint.x) * (180 / Math.PI);

            line.style.position = "absolute";
            line.style.transformOrigin = "0 0";
            line.style.transform = `rotate(${angle}deg)`;
            line.style.width = `${length}px`;
            line.style.height = "2px";
            line.style.backgroundColor = color;
            line.style.left = `${startPoint.x}px`;
            line.style.top = `${startPoint.y}px`;

            container.appendChild(line);
        },

// Helper function to mark a point (like center or edge point)
        addDebugPoint: function (point, color, container) {
            let pointMarker = document.createElement("div");
            pointMarker.style.position = "absolute";
            pointMarker.style.left = point.x + "px";
            pointMarker.style.top = point.y + "px";
            pointMarker.style.width = "10px";
            pointMarker.style.height = "10px";
            pointMarker.style.backgroundColor = color;
            pointMarker.style.borderRadius = "50%";
            pointMarker.style.zIndex = "1000";

            container.appendChild(pointMarker);
        },

        calculateAngleAndEdgePoint: function (sectionNumber, interviewCenterX, interviewCenterY, diameter) {
            let angle = 0;
            let edgeRight_point = {x: 0, y: 0};

            switch (sectionNumber) {
                case 2:
                    // Right edge
                    edgeRight_point = {
                        x: parseFloat(interviewCenterX) + diameter / 2,
                        y: parseFloat(interviewCenterY),
                    };
                    angle = 180;
                    break;
                case 3:
                    // Left edge
                    edgeRight_point = {
                        x: parseFloat(interviewCenterX) - diameter / 2,
                        y: parseFloat(interviewCenterY),
                    };
                    angle = 120;
                    break;
                case 4:
                    // Top edge
                    edgeRight_point = {
                        x: parseFloat(interviewCenterX) + diameter / 2,
                        y: parseFloat(interviewCenterY),
                    };
                    angle = 90;
                    break;
                case 5:
                    // Top edge
                    edgeRight_point = {
                        x: parseFloat(interviewCenterX),
                        y: parseFloat(interviewCenterY) - diameter / 2,
                    };
                    angle = 72;
                    break;
                case 6:
                    // Right edge
                    edgeRight_point = {
                        x: parseFloat(interviewCenterX) + diameter / 2,
                        y: parseFloat(interviewCenterY),
                    };
                    angle = 60;
                    break;
                default:
                    console.error("Unsupported section number: " + sectionNumber);
            }

            return {angle, edgeRight_point};
        },

        setCenterLabel: function (container) {
            let navbarHeight = document.getElementById("main-nav-interview").offsetHeight;

            let centerLabel = document.createElement("div");
            centerLabel.className = "bg-gray-900 text-white absolute rounded-full text-center align-middle p-3";
            centerLabel.id = "centerLabel";
            centerLabel.style.height = "50px";
            centerLabel.style.width = "50px";
            centerLabel.style.left = this.interview.center_x - 25 + "px";
            centerLabel.style.top = this.interview.center_y + navbarHeight - 25 + "px";
            centerLabel.innerText = this.centerLabel;

            container.appendChild(centerLabel);
        }
        ,
        capture_screenshot: async function (increase = true) {
            this.loading = true;
            let element = document.getElementById("sortingsdiv");
            let self = this;
            this.removeElementsFromScreenshot();

            let options = {
                width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
                height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                pixelRatio: 1,
            };

            await toPng(element, options)
                .then(function (dataUrl) {
                    self.addElementsHiddenBefore();
                    self.interview.structure.sorting[self.interview.structure.sortingNumber].sortingScreenshot = dataUrl.toString();

                    if (increase) {
                        self.interview.structure.sortingNumber++;
                    }

                    document.querySelectorAll(".sectionLabel").forEach((el) => el.remove());
                })
                .catch(function (e) {
                    self.addElementsHiddenBefore();
                    self.loading = false;
                });
        }
        ,
        notlastsorting: function () {
            return this.interview.additionalSorting && this.interview.structure.sortingNumber + 1 <= this.$store.state.newinterview.sortingtotal;
        }
        ,
        resetSelectedClassifier: function () {
            this.classifiers[0].forEach((value, key) => {
                let cl = document.getElementById("class" + key);
                if (cl) {
                    cl.classList.remove("border-4");
                    cl.classList.remove("p-1");
                    cl.classList.remove("border");
                    cl.classList.remove("border-red-500");
                }
            });
            this.$store.commit("updateselectedclassifier", {});
        }
        ,
        nextpage: async function () {

            let self = this;
            let isNetworkSorting = self.sorting[0].id === 2;
            let isQsort = self.sorting[0].id === 3;
            if (isNetworkSorting) {
                self.loading = true;
                setTimeout(function () {
                    self.interview.center_x = self.$refs["sortingContainer" + self.sortingtotal][0].center_x;
                    self.interview.center_y = self.$refs["sortingContainer" + self.sortingtotal][0].center_y;
                    self.createSections(self.divisions);
                    self.positionLabels(self.divisions);

                }, 1000);
                self.loading = false;
            } else if (isQsort) {
            }

            let notFirstPage = this.interview.currentPage != 0;
            if (this.notlastsorting() && notFirstPage) {
                await this.capture_screenshot();
                setTimeout(function () {
                    var labelDiv = document.getElementsByClassName("token-label");
                    for (var i = 0; i < labelDiv.length; i++) {
                        var label = labelDiv.item(i);
                        if (label.offsetWidth > 50) {
                            label.style.left = "-" + (label.offsetWidth - 50) / 2 + "px";
                        } else {
                            label.style.left = "0px";
                        }
                    }
                    self.loading = false;
                }, 50);

                this.resetSelectedClassifier();


                return;
            }

            if (this.interview.currentPage + 1 == 2) {
                await this.capture_screenshot(false);
            }

            this.interview.currentPage++;
            this.loading = false;


        }
        ,
        previouspage: function () {
            if (this.interview.additionalSorting && this.interview.structure.sortingNumber > 1 && this.interview.currentPage < 2) {
                this.interview.structure.sortingNumber--;
                this.addElementsHiddenBefore();
                setTimeout(function () {
                    var labelDiv = document.getElementsByClassName("token-label");
                    for (var i = 0; i < labelDiv.length; i++) {
                        var label = labelDiv.item(i);
                        if (label.offsetWidth > 50) {
                            label.style.left = "-" + (label.offsetWidth - 50) / 2 + "px";
                        } else {
                            label.style.left = "0px";
                        }
                    }
                }, 50);
                this.positionLabels(this.sections);
                this.resetSelectedClassifier();

                return;
            }


            if (this.interview.currentPage - 1 != 0) {
                this.interview.structure.sortingNumber = 1;
            }

            this.interview.currentPage--;
        }
        ,
        notLastPage: function () {
            return this.interview.currentPage != this.interview.pages;
        }
        ,
        lastPage: function () {
            return this.interview.currentPage == this.interview.pages;
        }
        ,
        noPostSortQuestions: function () {
            return this.interview.currentPage == 1 && this.questions.postsort.length == 0;
        }
        ,
        confirmnextpage: function () {
            if ((this.notLastPage() && !this.noPostSortQuestions()) || this.notlastsorting()) {
                this.dialog.show = true;
                this.dialog.title = "Section Finished";
                this.dialog.message = "Are you sure you want to <b>end</b> the section and go to the next?";
                this.dialog.confirmText = "Go";
                this.dialog.onConfirm = () => {
                    this.dialog.show = false;

                    this.nextpage();
                }
                this.dialog.onCancel = () => {
                    this.dialog.show = false;
                };
            } else if (this.lastPage() || this.noPostSortQuestions()) {
                // final dialog
                this.dialog.show = true;
                this.dialog.title = "Interview Finished";
                this.dialog.message = "Are you sure you want to <b>end</b> the interview and save?";
                this.dialog.confirmText = "Go";
                this.dialog.onConfirm = () => this.saveinterview();
                this.dialog.onCancel = () => {
                    this.dialog.show = false;
                };
            }
        }
        ,
        confirmpreviouspage: function () {
            this.dialog.show = true;
            this.dialog.title = "Previous Section";
            this.dialog.message = "Are you sure you want to <b>end</b> the section and go to the previous?";
            this.dialog.confirmText = "Go";
            this.dialog.onConfirm = () => {
                this.dialog.show = false;
                this.previouspage();
            }
            this.dialog.onCancel = () => {
                this.dialog.show = false;
            };
        }
        ,
        manageMultipleButton: function (a, id) {
            let existingItem = this.results.multi.find(item => item.id === id);
            let index = this.results.multi.indexOf(existingItem);

            if (index > -1) {
                this.results.multi.splice(index, 1);
            } else {
                this.results.multi.push({id: id, value: a});
            }
        }
        ,
        manageOneChoiceButton: function (a, id, qid) {
            let existingItem = this.results.onechoice.find(item => item.qid === qid);
            let index = this.results.onechoice.indexOf(existingItem);

            if (index > -1) {
                if (this.results.onechoice[index].value === a) {
                    this.results.onechoice.splice(index, 1);
                } else {
                    this.results.onechoice.splice(index, 1, {id: id, value: a, qid: qid});
                }
            } else {
                this.results.onechoice.push({id: id, value: a, qid: qid});
            }
        }
        ,
        saveinterview: async function () {
            let publicInterviewToken = this.getUrlVars()["t"];

            this.loading = true;
            let self = this;
            var screenshotsaved = false;

            if (Object.keys(this.interview.structure.sorting[this.sortingtotal] || {}).length === 0) {
                await this.capture_screenshot().then(function () {
                    screenshotsaved = true;
                });
            } else {
                screenshotsaved = true;
            }


            if (screenshotsaved) {
                this.interview.structure.sorting.filter(Boolean);


                for (let i = 1; i <= this.sortingtotal; i++) {
                    if (this.sorting[0].id == 2) {
                        clearInterval(this.$refs["sortingContainer" + i][0].interval);
                        localStorage.setItem("tokens" + i, null);
                        localStorage.removeItem("tokens" + i);
                    }

                    this.interview.structure.sorting[i].tokens = this.$refs["sortingContainer" + i][0].tokens.filter(function (o) {
                        return o.export === null || o.export === undefined;
                    });


                    this.interview.structure.sorting[i].tokens.forEach(function (token) {
                        if (token.valutation.section === "outside") {
                            token.valutation.sectionName = "outside";
                            token.valutation.section = -1;
                        } else {
                            token.valutation.sectionName = self.sectionNames[token.valutation.section];
                        }
                    });

                }

                this.interview.timeend = this.calculateDateTime();
                let sortings = this.interview.structure.sorting.filter(Boolean);
                let interview = {};

                if (this.sorting[0].id === 3 && this.extremeQuestion !== "") {
                    /// Q-Sort save the extreme questions, if any.
                    this.$refs["sortingContainer" + this.sortingtotal][0].extremes.answers.forEach((a) => {
                        this.results.extremes.push({
                            id: a.question.answerids,
                            text: a.answer,
                            question_id: a.question.qid,
                            token_id: a.token_id,
                        });
                    });

                }
                interview = {
                    ...interview,
                    results_questions: this.results,
                    time_end: this.interview.timeend,
                    time_start: this.interview.timestart,
                    study: this.study.id,
                    interviewed: this.interviewed,
                    sorting: sortings,
                    publicInterviewToken: publicInterviewToken ?? ""
                };


                window.axios
                    .post("../interviews", interview)
                    .then((response) => {

                        this.showToast(response.data + " redirecting to home . . .", "success");

                        setTimeout(function () {
                            window.imagepreset = null;
                            self.loading = false;
                            window.location.href = publicInterviewToken ? window.location.origin + self.productionUrl + "interview/done" : "../";
                        }, 1000);
                    })
                    .catch((error) => {

                        this.showToast(error + " error - Interview not saved", "danger");

                        self.loading = false;
                    });
            }
        }
        ,
        showToast: function (message, type) {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
        }
        ,
    }
    ,
}
;
</script>

<style scoped>
.fade-enter,
.fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}

#sortingsdiv {
    width: 100vw;
    height: 100vh;
}

.navigation-question-next {
    background-color: #ce223f;
    opacity: 0.25;
    position: absolute;
    top: 60%;
    right: 10px;
    color: white;
}

.navigation-question-next:hover {
    opacity: 1;
    color: white !important;
}

.navigation-question-previous {
    background-color: #ce223f;
    opacity: 0.25;
    position: absolute;
    top: 60%;
    left: 10px;
    color: white;
}

.navigation-question-previous:hover {
    opacity: 1;
    color: white !important;
}

#acab {
    transition: transform 0.23s;
}
</style>
