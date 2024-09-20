<template>
    <div class="flex">
        <!-- Replaced Modal -->
        <Modal
            v-if="showModal"
            :title="extremeQuestion.q"
            :visible="showModal"
            @confirm="answerExtremePlace"
            @cancel="toggleModal"
        >
            <textarea
                required
                id="extreme_question"
                v-model="extremes.temporaryAnswer"
                class="block w-full px-4 py-2 leading-normal bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring"
                type="text"
            ></textarea>
        </Modal>

        <div
            v-show="toggleCards"
            class="relative z-30 flex-wrap w-1/3 h-64 py-2 m-0 overflow-visible font-bold bg-blue-500 border-2 border-blue-600 text-white shadow-lg rounded-lg sm:text-xs md:text-sm lg:text-base remove-from-screenshot"
            style="left: 50%; transform: translateX(-50%);"
        >
            <div
                v-for="(t, index) in tokens"
                :key="index"
                :id="t.id"
                class="z-50 inline-flex m-1"
                style="width: 50px; height: 50px; z-index: 100"
                @click="updateselecttoken(t)"
                @mouseleave="hoverfalse(index)"
                @mouseover="hovertrue(index)"
            >
                <div
                    :class="
                        t.arrayofQsortPosition !== {} && t.arrayofQsortPosition
                            ? 'block opacity-50'
                            : 'block'
                    "
                >
                    <img
                        :src="t.image_path"
                        alt="token"
                        style="width: 50px; height: 50px; z-index: 100"
                    />
                </div>
                <div
                    v-show="t.hover"
                    id="tokendescription"
                    class="absolute z-50 w-auto p-2 overflow-visible text-white bg-gray-700 cards-description"
                >
                    <img
                        :alt="t.properties.description"
                        :src="t.image_path"
                        class="block"
                        style="
                            min-width: 150px;
                            min-height: 150px;
                            z-index: 100;
                        "
                    />
                    <p class="text-2xl font-bold text-white">{{ t.name }}</p>
                    <p class="text-sm text-gray-100">
                        {{ t.properties.description }}
                    </p>
                </div>
            </div>
        </div>

        <div
            id="qsort"
            :class="qsortClass"
            :style="
                'left: 50%;transform: translateX(-50%);' +
                'top:' +
                top.toString() +
                '%;'
            "
        >
            <div
                v-for="(column, key) in columns"
                :key="key"
                :style="'height:' + eachContainerHeight + 'px'"
                class="grid grid-cols-1 gap-0 align-middle"
            >
                <div :class="columnCss">
                    <div
                        v-for="index in column.length - 2"
                        :key="index"
                        v-model="arrayOfQsort[key][index]"
                        :class="
                            column[index - 1] === '---blank---'
                                ? squareCssEmpty
                                : squareCss
                        "
                        @click="
                            assignTokenToCell(
                                key,
                                index,
                                column[index - 1] === '---blank---'
                            )
                        "
                    >
                        <img
                            v-if="arrayOfQsort[key][index]"
                            :src="arrayOfQsort[key][index].image_path"
                            class="w-full h-full"
                        />
                    </div>

                    <div v-if="qsortshownumbers" :class="numberClass">
                        {{
                            column[column.length - 2] !== "--empty--"
                                ? column[column.length - 2]
                                : ""
                        }}
                    </div>
                    <div :class="anotherNumberClass">
                        {{
                            column[column.length - 1] !== "--empty--"
                                ? column[column.length - 1]
                                : ""
                        }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import {mapState} from "vuex";
import Modal from "../modal.vue";

export default {
    name: "q-sort",
    components: {Modal},
    props: [
        "columns",
        "itemsnumber",
        "maxnumber",
        "availabletokens",
        "qsortshownumbers",
        "extremeQuestion",
    ],
    data() {
        return {
            showModal: false,
            sizes: [8, 12, 16, 24, 32],
            distance: [0, 2, 3, 4, 5, 6, 7, 8],
            baseTop: 6,
            tokens: [],
            descriptionShowninToken: {},
            arrayOfQsort: [],
            extremes: {
                tokenId: -1,
                temporaryAnswer: "",
                answers: [],
                replacingToken: -1,
            },
        };
    },
    watch: {
        qsortSizeIndex: function (newVal, oldVal) {
            this.setCellSize();
        },
    },
    computed: {
        anotherNumberClass: function () {
            switch (this.qsortSizeIndex) {
                case 0:
                    return "bg-white text-black qsortlg:w-8 qsortmd:w-16 qsortsm:w-12 qsortlg:h-8 qsortmd:h-16 qsortsm:h-12 select-none inline-block align-top justify-center";
                case 1:
                    return "bg-white text-black qsortlg:w-12 qsortmd:w-16 qsortsm:w-12 qsortlg:h-16 qsortmd:h-12 qsortsm:h-12 select-none inline-block align-top justify-center";
                case 2:
                    return "bg-white text-black qsortlg:w-16 qsortmd:w-16 qsortsm:w-12 qsortlg:h-16 qsortmd:h-16 qsortsm:h-12 select-none inline-block align-top justify-center";
                case 3:
                    return "bg-white text-black qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 qsortlg:h-32 qsortmd:h-24 qsortsm:h-12 select-none inline-block align-top justify-center";
                case 4:
                    return "bg-white text-black qsortlg:w-32 qsortmd:w-16 qsortsm:w-12 qsortlg:h-64 qsortmd:h-32 qsortsm:h-12 select-none inline-block align-top justify-center";
                default:
                    return "error";
            }
        },
        numberClass: function () {
            switch (this.qsortSizeIndex) {
                case 0:
                    return "bg-white text-black qsortlg:w-8 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center";
                case 1:
                    return "bg-white text-black qsortlg:w-12 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center";
                case 2:
                    return "bg-white text-black qsortlg:w-16 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center";
                case 3:
                    return "bg-white text-black qsortlg:w-24 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center";
                case 4:
                    return "bg-white text-black qsortlg:w-32 qsortmd:w-16 qsortsm:w-12 qsortlg:h-12 qsortmd:h-8 qsortsm:h-8 select-none inline-block align-middle justify-center";
                default:
                    return "error";
            }
        },
        qsortClass: function () {
            return (
                "grid grid-cols-" +
                this.itemsnumber +
                " gap-0 text-center absolute mx-auto w-auto grid-flow-col"
            );
        },
        columnCss: function () {
            return (
                "qsortlg:w-" +
                this.sizes[this.qsortSizeIndex] +
                "  qsortmd:w-16 qsortsm:w-12 grid grid-rows-" +
                (this.maxnumber + 1) +
                " grid-flow-row gap-0 "
            );
        },
        squareCssEmpty: function () {
            return (
                "bg-pink-400 opacity-0 qsortlg:w-" +
                this.sizes[this.qsortSizeIndex] +
                "  qsortmd:w-16 qsortsm:w-12  qsortlg:h-" +
                this.sizes[this.qsortSizeIndex] +
                " qsortmd:h-16 qsortsm:h-12"
            );
        },
        squareCss: function () {
            const size = this.sizes[this.qsortSizeIndex];
            return `bg-gradient-to-r from-gray-900 to-white text-white border border-solid border-white qsortlg:w-${size} qsortmd:w-16 qsortsm:w-12 qsortlg:h-${size} qsortmd:h-16 qsortsm:h-12 select-none inline-block align-middle justify-center`;
        },
        basicWidth: function () {
            return this.sizes[this.qsortSizeIndex];
        },
        top: function () {
            return this.baseTop * this.distance[this.qsortDistanceIndex];
        },
        containerWidth: function () {
            return this.sizes[this.qsortSizeIndex] * this.itemsnumber * 2 * 2;
        },
        eachContainerHeight: function () {
            return this.sizes[this.qsortSizeIndex] * this.maxnumber * 2;
        },
        ...mapState({
            toggleCards: (state) => state.newinterview.toggleCards,
            sortingtotal: (state) => state.newinterview.sortingtotal,
            currentsorting: (state) => state.newinterview.sorting,
            currentpage: (state) => state.newinterview.page,
            selectedToken: (state) => state.newinterview.selectedToken,
            qsortSizeIndex: (state) => state.newinterview.qsortSizeIndex,
            qsortDistanceIndex: (state) =>
                state.newinterview.qsortDistanceIndex,
        }),
    },
    mounted() {
        this.createtokens();
    },
    created() {
        this.tokens = {};

        this.arrayOfQsort = this.columns;

        this.arrayOfQsort.forEach((o, i, a) =>
            a[i].forEach((x, j, b) => (b[j] = ""))
        );
        setTimeout(function () {
            let tokens = document.getElementsByClassName("cards");
            for (let i = 0; i < tokens.length; i++) {
                tokens[i].addEventListener("click", tokenListener, false);
            }

            let tokenListener = (e) => {
                let description = document.getElementById("tokendescription");
                self.mousePosition.x = e.clientX;
                self.mousePosition.y = e.clientY - 58;

                self.$refs["sortingContainer" + self.sortingtotal][0].forEach((section, i) => {
                    element.style.transform = "translateY(" + (e.clientY - 10) + "px)";
                    element.style.transform += "translateX(" + (e.clientX - 100) + "px)";
                    element.innerHTML = self.sectionNames[i];
                    tokenListener.classList.remove("invisible");
                });
            };
        }, 1500);

        let self = this;
        setTimeout(function () {
            self.setCellSize();
        }, 75);
    },
    methods: {
        checkIfTokenIsMovedOnTopOfAnotherExtreme: function (existingAnswer) {
            if (
                existingAnswer &&
                this.extremes.replacingToken !== existingAnswer.token_id
            ) {
                this.extremes.answers = this.extremes.answers.filter(a => a.token_id !== existingAnswer.token_id);
            }
        },
        answerExtremePlace() {
            // same token sorted again
            let existingAnswer = this.extremes.answers.find(
                (answer) => answer.token_id === this.extremes.tokenId
            );

            if (this.extremes.replacingToken !== -1) {

                // check if the sorted token was already there in the graph.
                this.checkIfTokenIsMovedOnTopOfAnotherExtreme(existingAnswer);
                let replaceExistingToken = this.extremes.answers.find(
                    (answer) => answer.token_id === this.extremes.replacingToken
                );
                replaceExistingToken.token_id = this.extremes.tokenId;
                replaceExistingToken.answer = this.extremes.temporaryAnswer;
                replaceExistingToken.question = this.extremeQuestion;
            } else if (!existingAnswer) {
                this.extremes.answers.push({
                    token_id: this.extremes.tokenId,
                    answer: this.extremes.temporaryAnswer,
                    question: this.extremeQuestion,
                });
            } else {
                existingAnswer.token_id = this.extremes.tokenId;
                existingAnswer.answer = this.extremes.temporaryAnswer;
                existingAnswer.question = this.extremeQuestion;
            }

            this.extremes.temporaryAnswer = "";
            this.toggleModal();
        },
        toggleModal(selectedTokenId = "") {
            this.showModal = !this.showModal;
            if (this.extremes.tokenId === -1) {
                this.extremes.tokenId = selectedTokenId;
            } else {
                this.extremes.tokenId = -1;
                if (this.extremes.replacingToken !== -1) {
                    this.extremes.replacingToken = -1;
                }
            }
        },
        setCellSize: function () {
            let windowSize = window.innerWidth;
            let widthMultiplier = this.sizes[this.qsortSizeIndex];
            let top = 6;
            let mediaQuerySM = windowSize > 639 && windowSize < 768;
            let mediaQueryMD = windowSize >= 768 && windowSize < 1366;
            let mediaQueryLG = windowSize >= 1366;

            top *= this.distance[this.qsortDistanceIndex];
            if (mediaQuerySM) {
                widthMultiplier = 12;
            } else if (mediaQueryMD) {
                widthMultiplier = 16;
            } else if (mediaQueryLG) {
                widthMultiplier = this.sizes[this.qsortSizeIndex];
            }

            let containerWidth = widthMultiplier * this.itemsnumber * 2 * 2;
            let element = document.getElementById("qsort");
            element.style.width = containerWidth.toString() + "px";

            element.style.top = top.toString() + "%";
        },
        assignTokenToCell: function (key, n, skip) {
            let tokenReadyToBeSorted = this.selectedToken && !skip;
            if (tokenReadyToBeSorted) {
                if (
                    (key === 0 || key === this.columns.length - 1) &&
                    this.extremeQuestion !== ""
                ) {
                    if (this.arrayOfQsort[key][n]) {
                        this.extremes.replacingToken = this.tokens.find(
                            (t) => t.id === this.arrayOfQsort[key][n].id
                        ).id;
                    }
                    this.toggleModal(this.selectedToken.id);
                }

                let tokenToChange = this.tokens.find(
                    (t) => t.id === this.selectedToken.id
                );
                let tokenAlreadySorted = tokenToChange.arrayofQsortPosition;
                if (tokenAlreadySorted) {
                    this.arrayOfQsort[tokenToChange.arrayofQsortPosition[0]][
                        tokenToChange.arrayofQsortPosition[1]
                        ] = {};
                }
                if (this.arrayOfQsort[key][n]) {
                    let tokenOccupyingSpace = this.tokens.find(
                        (t) => t.id === this.arrayOfQsort[key][n].id
                    );
                    delete tokenOccupyingSpace.arrayofQsortPosition;
                }
                let answerObject = this.extremes.answers.find(
                    (a) => a.token_id === tokenToChange.id
                );
                if (answerObject) {
                    this.extremes.answers = this.extremes.answers.filter(
                        (a) => a.token_id !== answerObject.token_id
                    );
                }

                this.arrayOfQsort[key][n] = this.selectedToken;
                tokenToChange.arrayofQsortPosition = [key, n];
                this.$store.commit("updateselectedtoken", {});
                tokenToChange.position = [
                    key + 1,
                    Math.abs(n - (this.arrayOfQsort[key].length - 1)),
                ];
            } else {
                if (
                    (key === 0 || key === this.columns.length - 1) &&
                    this.extremeQuestion !== "" &&
                    this.arrayOfQsort[key][n]
                ) {
                    let token_id = this.tokens.find(
                        (t) => t.id === this.arrayOfQsort[key][n].id
                    ).id;
                    let answerObject = this.extremes.answers.find(
                        (a) => a.token_id === token_id
                    );

                    this.extremes.replacingToken = token_id;
                    this.extremes.temporaryAnswer = answerObject.answer;
                    this.toggleModal(token_id);
                }
            }
        },
        updateselecttoken: function (token) {
            this.$store.commit("updateselectedtoken", token);
            this.$store.commit("updateToggleCards", !this.toggleCards);

            this.$forceUpdate();
        },
        hovertrue: function (i) {
            Vue.set(this.tokens[i], "hover", true);
            this.descriptionShowninToken = this.tokens[i];
            this.$forceUpdate();
        },
        hoverfalse: function (i) {
            Vue.set(this.tokens[i], "hover", false);
            this.descriptionShowninToken.hover = false;
            this.$forceUpdate();
        },
        createtokens: function () {
            this.tokens = this.availabletokens;
            for (let i = 0; i < this.tokens.length; i++) {
                let tokensorting =
                    this.tokens[i].author == 0 ? 0 : this.sortingtotal;
                this.tokens[i].valutation = {
                    circle: 0,
                    distance: 0,
                    sorting: tokensorting,
                    classifiers: [],
                };
                this.tokens[i].position = 0;
                this.tokens[i].drag = true;
                this.tokens[i].hover = false;
                this.tokens[i].properties = JSON.parse(
                    this.tokens[i].properties
                );
            }
        },
    },
};
</script>


<style scoped></style>
