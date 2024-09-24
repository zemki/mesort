<template>
  <section>
    <!-- Modal -->
    <div
        v-if="currentsorting === sorting"
        :class="'modal' +
                currentsorting +
                ' opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50 '
            "
    >
      <div
          class="absolute w-full h-full bg-gray-900 opacity-50"
          @click.prevent="togglePersonModal()"
      ></div>

      <div class="z-50 w-1/3 mx-auto bg-white modal-container">
        <div
            class="absolute top-0 right-0 z-50 flex flex-col items-center mt-4 mr-4 text-sm text-white cursor-pointer"
            @click.prevent="togglePersonModal()"
        >
          X
          <span class="text-sm">(Esc)</span>
        </div>

        <div class="w-auto px-6 py-4 text-left modal-content">
          <div class="flex items-center justify-between pb-3">
            <p class="text-2xl font-bold">
              {{ trans("Create a Person") }}
            </p>
            <div
                class="z-50 cursor-pointer"
                @click.prevent="togglePersonModal()"
            >
              X
            </div>
          </div>

          <!--Body-->
          <div class="w-2/3 mx-auto">
            <img
                :src="newtoken.base64"
                alt="person"
                class="m-auto w-25"
            />

            <label class="font-bold">{{
                trans("Person Name")
              }}</label>
            <input
                id="addPerson"
                v-model="newtoken.name"
                class="block w-full px-2 py-1 mt-2 leading-normal bg-gray-200 border border-gray-300 appearance-none focus:outline-none focus:ring"
                type="text"
            />
          </div>

          <!--Footer-->
          <div class="mt-4">
            <button
                @click.prevent="savetoken"
                type="button"
                class="inline-flex justify-center w-full px-4 py-2 mb-4 text-base font-medium text-white bg-blue-500 border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
            >
              {{ trans("Save and add new") }}
            </button>
          </div>
          <div class="">
            <button
                @click.prevent="savetoken('close')"
                type="button"
                class="inline-flex justify-center w-full px-4 py-2 mb-4 text-base font-medium text-white bg-blue-500 border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
            >
              {{ trans("Save and Close") }}
            </button>
          </div>
          <div class="">
            <button
                @click="togglePersonModal()"
                type="button"
                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-black bg-gray-100 border border-transparent shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
            >
              {{ trans("Cancel") }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
        :id="'sortingc' + currentsorting"
        :class="'sorting-container' + currentsorting"
    >
      <div class="flex w-auto h-auto">
        <h2
            class="absolute p-4 font-bold text-center text-white uppercase bg-blue-500 sm:text-xs md:text-xs lg:text-base md:mt-5 remove-from-screenshot"
            style="right: 5%; top: 15%"
        >
          {{ trans("Persons") }}
        </h2>
        <div
            class="absolute z-20 flex h-auto sm:text-xs md:text-xs lg:text-base md:mt-10 token-container-created has-to-move"
            style="
                        top: 20%;
                        right: 5%;
                        flex-wrap: wrap;
                        width: 16.6666666667%;
                    "
        >
          <div
              v-for="(t, index) in tokens"
              :key="index"
              v-if="t.render !== false"
              v-show="
                            t.author === 0 &&
                            t.valutation.sorting === currentsorting
                        "
              :id="t.id"
              :class="'token' + currentsorting"
              class="inline-block sortingstart createdtokens"
              @click="assignclassifier(t.id, index)"
          >
            <div
                v-if="t.isDragged"
                class="absolute z-40 items-center justify-center text-sm font-bold text-center text-white bg-red-500 rounded-lg cursor-pointer hover:bg-red-300 delete-token-button remove-from-screenshot"
                @click="confirmdeletetoken(t)"
            >
              x
            </div>

            <img
                :src="t.image_path"
                alt="token image"
                class="token-image"
            />
            <img
                v-for="(classifiersimages, cindex) in t.valutation
                                .classifiers"
                :key="cindex"
                :alt="'classifier' + cindex"
                :src="classifiersimages.dirname"
                :style="
                                'margin-left:' +
                                (25 + (cindex + 1) * 27) +
                                'px;width: 25px;height: 25px;margin-top:12px;z-index:99;'
                            "
                class="absolute classifierimage"
            />

            <span
                v-if="t.drag"
                :id="'tokenlabel' + t.id"
                class="tag is-dark token-label"
                v-html="t.name"
            ></span
            ><br/>

            <div
                v-show="debug"
                class="relative block rounded-none debugDiv"
                style="width: 200px; top: 38px"
            >
              <div
                  v-if="t.drag"
                  class="inline-block w-auto px-2 text-sm text-white bg-blue-500 rounded-none"
                  v-html="'Section: ' + t.valutation.section"
              ></div>
              <div
                  v-if="t.drag"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="'Circle ' + t.valutation.circle"
              ></div>
              <div
                  v-if="t.drag"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="
                                    'Center Distance ' + t.valutation.distance
                                "
              ></div>
              <div
                  v-if="t.drag"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="'Position X ' + t.position.x"
              ></div>
              <div
                  v-if="t.drag"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="'Position Y ' + t.position.y"
              ></div>
              <div
                  v-if="t.drag && t.percentagePosition"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="
                                    '% position X ' + t.percentagePosition.x
                                "
              ></div>
              <div
                  v-if="t.drag && t.percentagePosition"
                  class="inline-block text-sm text-white bg-blue-500 rounded-none"
                  v-html="
                                    '% position Y ' + t.percentagePosition.y
                                "
              ></div>
              <div
                  v-if="t.drag && t.valutation.sorting"
                  class="inline-block w-auto px-2 text-sm text-white bg-blue-500 rounded-none"
                  v-html="'Sorting ' + t.valutation.sorting"
              ></div>
            </div>
          </div>
        </div>
      </div>
      <div v-show="debug" id="center"></div>
      <div v-show="debug" id="currentToken"></div>
    </div>

    <custom-dialog v-if="dialog.show" :title="dialog.title" :message="dialog.message"
                   :confirm-text="dialog.confirmText" :on-confirm="dialog.onConfirm"
                   :on-cancel="dialog.onCancel"></custom-dialog>
    <custom-toast v-if="toast.show" :message="toast.message" :type="toast.type" :duration="toast.duration"
                  @close="toast.show = false"></custom-toast>
  </section>

</template>

<script>
import interact from "interactjs";
import {mapState} from "vuex";
import CustomToast from "../CustomToast.vue";
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
  // 173 Min Key  hyphen/underscore Hey
  // 61 Plus key  +/= key
});

export default {
  props: ["availabletokens", "circles", "sections", "sorting"],
  data() {
    return {
      tokens: [],
      baseUrl: window.location.origin || '',
      newtoken: {
        name: "",
        base64: "data:image/png;base64,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",
        file: "/images/presets_tokens/person.png",
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
      classifiermode: false,
      selectedclassifier: {},
      mousePosition: {x: 0, y: 0},
      interval: "",
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
        duration: 1000,
      },
    };
  },
  components: {
    CustomToast,
    CustomDialog,
  },
  computed: {
    ...mapState({
      fetchtoken: (state) => state.fetchtoken,
      sortingtotal: (state) => state.newinterview.sortingtotal,
      currentsorting: (state) => state.newinterview.sorting,
      currentpage: (state) => state.newinterview.page,
    }), sectionNumber: function () {
      return this.sections.length;
    },
  },
  watch: {
    currentpage: function (newVal, oldVal) {
      let self = this;
      setTimeout(function () {
        if (newVal == 1) {
          self.getbounds();
          self.setlabelposition();
          self.setDebugDivPosition();
        }
      }, 100);
    },
    currentsorting: function (newVal, oldVal) {
      let self = this;
      setTimeout(function () {
        self.$forceUpdate();
      }, 1000);
    },
    fetchtoken: function (newVal, OldVal) {
      let urlParams = new URLSearchParams(window.location.search);
      let study_id = urlParams.get("study");
      if (newVal) {
        axios
            .post("../api/v1/gettokens", {study: study_id})
            .then((response) => {
              this.tokens.push(response.data);
              this.tokens[this.tokens.length - 1].valutation = {
                circle: 0,
                distance: 0,
                sorting: this.currentsorting,
                classifiers: [],
                section: "outside",
              };
              this.tokens[this.tokens.length - 1].position = 0;
              this.tokens[this.tokens.length - 1].drag = true;

              this.showToast("Person was successfully added.",
                  "success",
                  1000
              );

              this.$store.commit("setFetchToken", false);

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
  created() {

    let self = this;
    self.newtoken.dirname = this.productionUrl + self.newtoken.dirname;

    const tokens = this.openStorage();
    if (tokens && tokens.length > 0) {
      self.tokens = tokens;
      self.tokens.forEach(function (token) {
        token.valutation = {
          circle: 0,
          distance: 0,
          sorting: self.currentsorting,
          classifiers: [],
          section: "outside",
        };
      });
      self.$forceUpdate();
    } else {
      self.tokens = {};
      self.createtokens();
    }

    setTimeout(function () {
      self.$nextTick(function () {
        self.interval = window.setInterval(() => {
          self.saveStorage();
          // save changes into localStorage
        }, 1000);
      });
    }, 500);

    window.addEventListener("keydown", (e) => {
      if (e.keyCode == 66 && e.ctrlKey) {
        self.debugMode();
      }
    });

    setTimeout(function () {
      self.getbounds();
      self.setlabelposition();
      self.setDebugDivPosition();
    }, 200);

    this.draggable();
    this.$store.commit("updateloadedsorting", this.sortingtotal);
  },
  methods: {
    togglePersonModal() {
      this.$store.dispatch("togglePersonModal");
    },
    openStorage() {
      return JSON.parse(
          localStorage.getItem("tokens" + this.currentsorting)
      );
    },
    saveStorage() {
      localStorage.setItem(
          "tokens" + this.currentsorting,
          JSON.stringify(this.tokens)
      );
    },
    savetoken(close) {
      let self = this;

      self.newtoken.ispreset = true;
      let urlParams = new URLSearchParams(window.location.search);
      let study_id = urlParams.get("study");

      self.newtoken.file = self.baseUrl + self.productionUrl + self.newtoken.file;
      if (self.newtoken.name !== "") {
        axios
            .post("../api/v1/savetoken", {
              token: self.newtoken,
              study: study_id,
            })
            .then((response) => {
              this.$store.commit("setFetchToken", true);
              self.newtoken.name = "";
              if (close === "close") {
                this.togglePersonModal();
              }
            })
            .catch((error) => {
            });
      }
    },
    // Check if a point is within a given radius
    isWithinRadius: function (v, radiusSquared) {
      return v.x * v.x + v.y * v.y <= radiusSquared;
    },
    // Get the angle of the vector relative to the center
    getAngle: function (v) {
      return Math.atan2(v.y, v.x); // In radians
    },
    // Normalize the angle to be between 0 and 2*PI
    // we do this because the angle of the point relative to the center can be negative
    normalizeAngle: function (angle) {
      return (angle + 2 * Math.PI) % (2 * Math.PI);
    },
    isInsideSector: function (point, center, sectorStart, sectorEnd, radiusSquared) {
      // Offset the token's position relative to the center adding 25 to the x and y to get the center of the token
      let relPoint = {
        x: point.x + 25 - center.x,
        y: point.y + 25 - center.y,
      };

      // Get the angle of the point relative to the center
      let pointAngle = this.normalizeAngle(this.getAngle(relPoint));
      let startAngle = this.normalizeAngle(this.getAngle(sectorStart));
      let endAngle = this.normalizeAngle(this.getAngle(sectorEnd));

      // Check if the point is within the radius
      if (!this.isWithinRadius(relPoint, radiusSquared)) {
        return false;
      }

      // Check if the point angle lies within the sector's start and end angles
      if (startAngle < endAngle) {
        return pointAngle >= startAngle && pointAngle <= endAngle;
      } else {
        // If the sector crosses the 0-degree line (i.e., start > end), we handle the wrap-around
        return pointAngle >= startAngle || pointAngle <= endAngle;
      }
    },

    addDebugMarker: function (point, color) {
      // Create a marker on the page to visualize where the software thinks the token is
      const marker = document.createElement('div');
      marker.style.position = 'absolute';
      marker.style.left = (point.x + window.innerWidth / 2) + 'px';
      marker.style.top = (point.y + window.innerHeight / 2) + 'px';
      marker.style.width = '10px';
      marker.style.height = '10px';
      marker.style.backgroundColor = color;
      marker.style.borderRadius = '50%';
      marker.style.zIndex = '1000';
      document.body.appendChild(marker);
    },
    resetSelectedClassifier: function () {
      let x = document
          .getElementById("classifiercontainer")
          .querySelectorAll("img");
      let i;
      for (i = 0; i < x.length; i++) {
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
          this.tokens[index].valutation.classifiers.splice(
              classifierindex,
              1
          );
        } else {
          this.tokens[index].valutation.classifiers.push(
              this.$store.state.newinterview.selectedclassifier
          );
        }

        this.resetSelectedClassifier();
      }
    },
    showToast(message, type) {
      this.toast.message = message;
      this.toast.type = type;
      this.toast.show = true;
      this.toast.duration = 1000;
    },
    confirmdeletetoken: function (token) {
      event.preventDefault(); // Prevent default behavior
      event.stopPropagation(); // Stop propagation if necessary

      this.dialog.show = true;
      this.dialog.title = "Delete Token";
      this.dialog.message = "Are you sure you want to delete this token?";
      this.dialog.confirmText = "Delete";
      this.dialog.onConfirm = () => {
        this.deletetoken(token);
        this.showToast("Token was successfully deleted.", "success");
        this.dialog.show = false;
      };
      this.dialog.onCancel = () => {
        this.dialog.show = false;
      };
    },
    deletetoken: function (token) {
      axios.post("../api/v1/deletetoken", {
        study: this.tokens[0].pivot.study_id,
        token: token,
      })
          .then((response) => {
            let IndexTokenToDelete = this.tokens.findIndex(function (currentObject) {
              return currentObject.id === token.id;
            });

            this.tokens[IndexTokenToDelete].export = false;
            this.tokens[IndexTokenToDelete].render = false;
            document.getElementById(token.id).style.opacity = "0.0";

            this.$forceUpdate();
          })
          .catch((error) => {
            this.showToast("Error deleting token.", "error");
          });
    },
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


            let circleSize = document.querySelector(
                ".round-sorting" + self.circles
            ).offsetWidth;
            let radiusSquared = (circleSize * circleSize) / 4;
            for (let i = 0; i < self.sectionNumber; i++) {
              const section = self.sections[i];
              if (self.isInsideSector(self.tokens[tokenArrayIndex].position, section.center, section.start, section.end, radiusSquared)) {
                console.log("Token is in section", i);
                self.tokens[tokenArrayIndex].valutation.section = i;
                break;  // Exit the loop when the condition is met
              } else {
                console.log("Token is outside the circle");
                self.tokens[tokenArrayIndex].valutation.section = "outside the circle";
              }
            }
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

      target.style.webkitTransform = target.style.transform =
          "translate(" + x + "px, " + y + "px)";

      target.setAttribute("data-x", x);
      target.setAttribute("data-y", y);
    },
    dragMoveListener: function (event) {
      if (!this.classifiermode) {
        this.moveToken(event);
      }
    },
    getElementGlobalCoordinates: function (token) {
      var childrenPos = token.getBoundingClientRect(),
          relativePos = {};

      (relativePos.top = childrenPos.top - this.parentPos.top),
          (relativePos.right = childrenPos.right - this.parentPos.right),
          (relativePos.bottom =
              childrenPos.bottom - this.parentPos.bottom),
          (relativePos.left = childrenPos.left - this.parentPos.left);
      relativePos.x = childrenPos.x;
      relativePos.y = childrenPos.y;

      return relativePos;
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
    getbounds: function () {
      let self = this;

      setTimeout(function () {
        if (self.currentsorting == 1) {
          self.parentPos = document
              .querySelector(
                  ".sorting-container" + self.currentsorting
              )
              .getBoundingClientRect();
          self.bounds.top = self.getElementGlobalCoordinates(
              document.querySelector(".round-sorting" + self.circles)
          ).top;
          self.bounds.left = self.getElementGlobalCoordinates(
              document.querySelector(".round-sorting" + self.circles)
          ).left;
          self.bounds.right = self.getElementGlobalCoordinates(
              document.querySelector(".round-sorting" + self.circles)
          ).right;
          self.bounds.bottom = self.getElementGlobalCoordinates(
              document.querySelector(".round-sorting" + self.circles)
          ).bottom;
          self.calculateCircleCenter();
          self.$store.commit("setCenterAndBounds", {
            center_x: self.center_x,
            center_y: self.center_y,
            bounds: self.getElementGlobalCoordinates(
                document.querySelector(
                    ".round-sorting" + self.circles
                )
            ),
            parentPos: document
                .querySelector(
                    ".sorting-container" + self.currentsorting
                )
                .getBoundingClientRect(),
          });
        } else {
          self.parentPos = self.$store.state.newinterview.parentPos;
          self.bounds.top = self.$store.state.newinterview.bounds.top;
          self.bounds.left =
              self.$store.state.newinterview.bounds.left;
          self.bounds.right =
              self.$store.state.newinterview.bounds.right;
          self.bounds.bottom =
              self.$store.state.newinterview.bounds.bottom;
          self.center_x = parseFloat(
              self.$store.state.newinterview.center_x
          );
          self.center_y = parseFloat(
              self.$store.state.newinterview.center_y
          );
        }
      }, 350);

      setTimeout(function () {
        var d = document.getElementById("center");
        d.style.position = "relative";
        d.style.backgroundColor = "red";
        d.style.width = "4px";
        d.style.height = "4px";

        d.style.left = self.center_x - 2 + "px";
        d.style.top = self.center_y - 2 + "px";
      }, 100);
    },
    createtokens: function () {
      this.tokens = this.availabletokens;
      for (var i = 0; i < this.tokens.length; i++) {
        let tokensorting =
            this.tokens[i].author == 0 ? 0 : this.sortingtotal;
        this.tokens[i].valutation = {
          circle: 0,
          distance: 0,
          sorting: tokensorting,
          classifiers: [],
          section: 0,
        };
        this.tokens[i].position = 0;
        this.tokens[i].drag = true;
      }
    },
    setlabelposition: function () {
      var labelDiv = document.getElementsByClassName("token-label");
      for (var i = 0; i < labelDiv.length; i++) {
        var label = labelDiv.item(i);
        if (label.offsetWidth > 50) {
          label.style.left =
              "-" + (label.offsetWidth - 50) / 2 + "px";
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
  },
};
</script>
<style scoped>
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
</style>
