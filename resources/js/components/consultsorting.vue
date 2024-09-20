<template>
    <section style="margin-left:auto;margin-right:auto;position: fixed;">
        <button class="button is-danger" v-if="!loading" id="downloadbutton"
                style="display: flex;z-index: 1000;margin: 5px;position: fixed;" @click="download">{{ trans('export PDF') }}
        </button>
        <img v-show="!loading" :src="screenshot" id="sorting"
             style="max-width: 100%;max-height: 100%;position:fixed;left:10%">
        <div class="fixed" style="top:50%;left: 50%;"
             :class="loading? 'loading' : ''"></div>
        <div id="bottom" class="flex fixed inline" v-show="!loading"
             style="z-index: 1000;margin: 5px;bottom:0px;width:100%;background-color:#e9e7e3">
            <span style="left:50%;display: inline-flex;margin: 0 auto;">{{studyname}} - {{interviewname}}</span>
        </div>
    </section>
</template>

<script>
    import jsPDF from 'jspdf'


    $(document).keydown(function (event) {
        if (event.ctrlKey == true && (event.which == '61' || event.which == '107' || event.which == '173' || event.which == '109' || event.which == '187' || event.which == '189')) {
            event.preventDefault();
        }
        // 107 Num Key  +
        // 109 Num Key  -
        // 173 Min Key  hyphen/underscor Hey
        // 61 Plus key  +/= key
    });

    $(window).bind('mousewheel DOMMouseScroll', function (event) {
        if (event.ctrlKey == true) {
            event.preventDefault();
        }
    });


    export default {
        props: ['studyname', 'interviewname', 'screenshot'],
        data() {
            return {
                sortableOptions: {
                    chosenClass: 'is-selected'
                },
                tokens: [],
                loading: false,
                loadingprogress: 0,
                drag: false,
                parentPos: "",
                tokenvalues: "",
                maxvalue: 100,
                minvalue: 0,
                bounds: {},
                outmost: 0,
                center_x: 0,
                center_y: 0,
                startingWidth: 100,
                startingHeigth: 100,

            }
        }
        , created() {
            let self = this;
            this.tokens = this.atokens;

        },
        methods: {
            download() {

                this.loadingprogress = 30;
                let self = this;

                let opt = {
                    optimized: true,
                    logging: false
                };
                self.loadingprogress = 45;

                document.querySelector("#sorting").style.width = document.getElementById("sorting").offsetWidth + "px";
                document.querySelector("#sorting").style.height = document.getElementById("sorting").offsetHeight + "px";
                document.querySelector("#bottom").style.width = document.getElementById("bottom").offsetWidth + "px";
                document.querySelector("#bottom").style.height = document.getElementById("bottom").offsetHeight + "px";
                var all = document.getElementsByTagName("*");

                for (var i = 0, max = all.length; i < max; i++) {
                    all[i].style.height = all[i].offsetHeight + "px";
                    all[i].style.width = all[i].offsetWidth + "px";
                }

                html2canvas(document.body, opt).then(function (canvas) {
                    self.loading = true;
                    self.loadingprogress = 55;

                    var jpegUrl = canvas.toDataURL("image/svg+xml").toString();

                    var img = new Image();
                    self.loadingprogress = 60;

                    img.src = jpegUrl;
                    self.loadingprogress = 80;
                    img.onload = function () {
                        self.loadingprogress = 90;

                        var pdf = new jsPDF('l', 'px', 'a4', true);
                        var left = 15, top = 40;

                        var width = pdf.internal.pageSize.getWidth();
                        var height = pdf.internal.pageSize.getHeight();

                        width = width * 1;
                        height = height * 1;

                        pdf.text(5, 25, "Mesort");
                        pdf.addImage(img, 'SVG', 5, 0, 180, 140, undefined, 'FAST');
                        pdf.save(self.studyname + ' - ' + self.interviewname + '.pdf');

                        self.loading = false;
                        self.loadingprogress = 0;

                    };
                });

            },
            handleResize: function () {
                console.log("eda");
                settokenposition();
            },
            beforeDestroy: function () {
                window.removeEventListener('resize', this.handleResize)
            }
        }
    }
</script>
