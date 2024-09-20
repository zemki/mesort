<template>
    <section v-show="isComponentModalActive">
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            {{ trans("Create new token") }}
                        </h3>
                    </div>
                    <div class="flex items-center justify-center mx-auto">
                        <img src="" alt="token image" id="tokenImage" class="flex-shrink-0 p-0 mx-auto my-4"
                             style="width: 100px"/>
                    </div>
                    <div class="block text-center file">
                        <input type="file" @change="handleFileUpload" accept="image/*" class="hidden" ref="fileInput"/>
                        <button type="button" @click="triggerFileUpload"
                                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 md:w-auto sm:w-4 hover:text-white">
                            <div class="text-center md:text-xs">{{ trans("Click to upload") }}</div>
                        </button>
                    </div>
                    <div class="my-4">
                        <label for="combobox" class="block text-sm font-medium text-gray-700">{{
                                trans("Preset tokens")
                            }}</label>
                        <div class="relative -mt-4">
                            <input v-model="file" @focusout="activatecombo" @focus="activatecombo" type="text"
                                   class="w-full py-2 pl-3 pr-12 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm"
                                   role="combobox" aria-controls="options" aria-expanded="false"/>
                            <button type="button"
                                    class="absolute inset-y-0 right-0 flex items-center px-2 rounded-r-md focus:outline-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <ul v-show="showCombobox"
                                class="absolute z-10 w-full py-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                id="options" role="listbox">
                                <li @mousedown="setTokenFromPreset(option.dirname)"
                                    class="relative py-2 pl-3 text-gray-900 cursor-default select-none max-h-52 pr-9"
                                    id="option-0" role="option" tabindex="-1"
                                    v-for="(option, indexj) in newinterview.imagepreset" :key="indexj">
                                    <div class="flex items-center">
                                        <img :src="option.dirname" :alt="option.basename"
                                             class="flex-shrink-0 w-6 h-6 rounded-full"/>
                                        <span class="ml-3 truncate">{{ option.basename }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-600 focus-within:border-blue-600">
                        <label for="name"
                               :class="error ? 'block text-xs font-medium text-red-500' : 'block text-xs font-medium text-gray-900'">{{
                                trans("Token Name")
                            }}</label>
                        <input v-model="newinterview.newtoken.name" type="text" name="name" id="name"
                               class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm"
                               placeholder=". . ."/>
                    </div>
                    <div class="mt-4">
                        <button @click="saveToken" type="button"
                                class="inline-flex justify-center w-full px-4 py-2 mb-4 text-base font-medium text-white bg-blue-500 border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            {{ trans("Save") }}
                        </button>
                    </div>
                    <div class="">
                        <button @click="closeModal" type="button"
                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-black bg-gray-100 border border-transparent shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            {{ trans("Cancel") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {mapState} from "vuex";

export default {
    props: [],
    data() {
        return {
            isComponentModalActive: false,
            file: null,
            fileUpload: null,
            showCombobox: false,
            ispreset: false,
            base64: "",
            image_path: "",
            error: false,
        };
    },
    created() {
        this.newinterview.newtoken.file = {name: ""};
        this.newinterview.newtoken = {name: ""};
    },
    computed: {
        ...mapState(["newinterview"]),
    },
    methods: {
        closeModal: function () {
            document.getElementById("tokenImage").src = "data:image/gif;base64,R0lGODlhAQABAPAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
            this.file = null;
            this.fileUpload = null;
            this.base64 = "";
            this.image_path = "";
            this.error = false;
            this.newinterview.newtoken = {name: ""};
            this.newinterview.newtokenmodal = false;
        },
        handleFileUpload(event) {
            this.fileUpload = event.target.files[0];
            if (!this.fileUpload) return;
            const reader = new FileReader();
            reader.onload = (fileEvent) => {
                const im = new Image();
                im.onload = () => {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    canvas.width = 100;
                    canvas.height = 100;
                    if (im.width < 100 || im.height < 100) {
                        ctx.drawImage(im, 0, 0, 100, 100, 0, 0, canvas.width, canvas.height);
                    } else {
                        this.drawImageProp(ctx, im, 0, 0, canvas.width, canvas.height);
                    }
                    const newimgUri = canvas.toDataURL("image/*").toString();
                    document.getElementById("tokenImage").src = newimgUri;
                    this.newinterview.newtoken.base64 = newimgUri;
                };
                im.src = fileEvent.target.result;
            };
            reader.readAsDataURL(this.fileUpload);

            if (!this.newinterview.newtoken.name) {
                this.newinterview.newtoken.name = this.fileUpload.name;
            }
        },
        triggerFileUpload() {
            this.$refs.fileInput.click();
        },
        drawImageProp: function (ctx, img, x, y, w, h, offsetX, offsetY) {
            if (arguments.length === 2) {
                x = y = 0;
                w = ctx.canvas.width;
                h = ctx.canvas.height;
            }

            offsetX = typeof offsetX === "number" ? offsetX : 0.5;
            offsetY = typeof offsetY === "number" ? offsetY : 0.5;

            if (offsetX < 0) offsetX = 0;
            if (offsetY < 0) offsetY = 0;
            if (offsetX > 1) offsetX = 1;
            if (offsetY > 1) offsetY = 1;

            var iw = img.width,
                ih = img.height,
                r = Math.min(w / iw, h / ih),
                nw = iw * r,
                nh = ih * r,
                cx,
                cy,
                cw,
                ch,
                ar = 1;

            if (nw < w) ar = w / nw;
            if (Math.abs(ar - 1) < 1e-14 && nh < h) ar = h / nh;
            nw *= ar;
            nh *= ar;

            cw = iw / (nw / w);
            ch = ih / (nh / h);

            cx = (iw - cw) * offsetX;
            cy = (ih - ch) * offsetY;

            if (cx < 0) cx = 0;
            if (cy < 0) cy = 0;
            if (cw > iw) cw = iw;
            if (ch > ih) ch = ih;

            ctx.drawImage(img, cx, cy, cw, ch, x, y, w, h);
        },
        saveToken: function () {
            if (!this.newinterview.newtoken.name) {
                this.error = true;
            } else {
                let name = "study";
                let self = this;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    result = regex.exec(this.url)[2].replace(/\+/g, " ");

                this.$store.dispatch("saveToken", result).then(function () {
                    self.error = false;
                    document.getElementById("tokenImage").src = "data:image/gif;base64,R0lGODlhAQABAPAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
                    self.file = null;
                    self.fileUpload = null;
                    self.base64 = "";
                    self.image_path = "";
                    self.newinterview.newtoken = {name: ""};
                    self.newinterview.newtokenmodal = false;
                });
            }
        },
        setTokenFromPreset: function (file = "") {
            this.newinterview.newtoken.file = file;
            if (file !== "") this.fileUpload = file;
            if (this.fileUpload !== "") {
                console.warn("fileupload is not empty!");
                let url = this.fileUpload;
                this.image = "";
                window
                    .axios({
                        method: "get",
                        url: url,
                        responseType: "arraybuffer",
                        headers: {
                            "Content-type": "image/*",
                        },
                    })
                    .then((response) => {
                        let image = btoa(
                            new Uint8Array(response.data).reduce((data, byte) => data + String.fromCharCode(byte), "")
                        );

                        let base64 = `data:${response.headers["content-type"].toLowerCase()};base64,${image}`;
                        let im = new Image();
                        im.src = base64;
                        let self = this;
                        im.onload = function () {
                            let canvas = document.createElement("canvas"),
                                ctx = canvas.getContext("2d");
                            canvas.width = 100;
                            canvas.height = 100;
                            if (im.width < 100 || im.height < 100) {
                                ctx.drawImage(im, 0, 0, 100, 100, 0, 0, canvas.width, canvas.height);
                            } else {
                                self.drawImageProp(ctx, im, 0, 0, canvas.width, canvas.height);
                            }

                            let newimgUri = canvas.toDataURL("image/*").toString();

                            document.getElementById("tokenImage").src = newimgUri;

                            self.base64 = newimgUri;
                            self.ispreset = true;
                            self.newinterview.newtoken.base64 = newimgUri;
                            self.newinterview.newtoken.ispreset = true;
                        };
                    });
                if (this.file.name == "") {
                    this.file.name = this.fileUpload.basename;
                    this.newinterview.newtoken.file.name = this.newinterview.newtoken.file.file.basename;
                }
            }
            this.$store.commit("newinterview", this.newinterview);
        },
        activatecombo: function () {
            this.showCombobox = !this.showCombobox;
            this.$forceUpdate();
        },
    },
    watch: {
        "newinterview.newtokenmodal": function (newVal, oldVal) {
            this.isComponentModalActive = newVal;
        },
    },
};
</script>

<style scoped>
img[src=""] {
    content: url("data:image/gif;base64,R0lGODlhAQABAPAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");
}
</style>
