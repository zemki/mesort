<template>
    <div
        class="mt-10 -mx-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 md:rounded-lg"
    >
        <Loading :isLoading="loading"/>
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
                <th
                    scope="col"
                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                >
                    id
                </th>
                <th
                    scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                >
                    {{ trans("Author") }}
                </th>
                <th
                    scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                >
                    {{ trans("Interviewee") }}
                </th>
                <th
                    scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                >
                    {{ trans("Start") }}
                </th>
                <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                >
                    {{ trans("End") }}
                </th>
                <th
                    scope="col"
                    class="relative text-sm py-3.5 pl-3 pr-4 sm:pr-6"
                >
                    {{ trans("Actions") }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(interview, index) in interviews"
                :key="index"
                class="hover:bg-gray-100"
            >
                <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                    {{ interview.id }}
                </td>
                <td
                    class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                >
                    {{ interview.author }}
                </td>
                <td
                    class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                >
                    {{ interview.interviewed }}
                </td>
                <td
                    class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                >
                    {{ formatdate(interview.start) }}
                </td>
                <td class="px-3 py-3.5 text-sm text-gray-500">
                    {{ formatdate(interview.end) }}
                </td>
                <td
                    class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-sm font-medium"
                >
                    <div class="flex flex-col space-y-2 items-end">
                        <a
                            :href="
                                    productionUrl +
                                   'interview/' +
                                    interview.id +
                                    '/show'
                                "
                        >
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                {{ trans("View") }}
                            </button>
                        </a>
                        <a
                            :href="
                                    productionUrl +
                                    'export/' +
                                    interview.id +
                                    '/interview'
                                "
                            target="_blank"
                        >
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                {{ trans("Export") }}
                            </button>
                        </a>
                        <a href="#" @click="confirmdelete(interview.id)">
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white bg-red-500 border border-gray-300 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                {{ trans("Delete") }}
                            </button>
                        </a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <custom-dialog v-if="dialog.show" :title="dialog.title" :message="dialog.message"
                       :confirm-text="dialog.confirmText" :on-confirm="dialog.onConfirm"
                       :on-cancel="dialog.onCancel"></custom-dialog>
    </div>
</template>

<script>
import Pencil from "vue-material-design-icons/Pencil.vue";
import moment from "moment";
import CustomDialog from "../CustomDialogue.vue";
import Loading from "../Loading.vue";

export default {
    props: ["interviews"],
    components: {
        Pencil,
        CustomDialog,
        Loading
    },
    data() {
        return {
            sortIcon: "arrow-up",
            sortIconSize: "is-small",
            defaultSortDirection: "asc",
            dialog: {
                show: false,
                title: "",
                message: "",
                confirmText: "",
                onConfirm: null,
                onCancel: null,
            },
        };
    },

    computed: {},
    methods: {
        formatdate: function (date) {
            //moment.defaultFormat = "DD.MM.YYYY HH:mm";
            var localeData = moment.localeData();
            var format =
                localeData.longDateFormat("L") +
                " " +
                localeData.longDateFormat("LT");

            //console.log((navigator.languages && navigator.languages.length) ? navigator.languages[0] : navigator.language);
            return moment(String(date)).format(this.getLocaleDateString());
        },
        confirmdelete: function (id) {
            this.dialog.show = true;
            this.dialog.title = "Add Sorting";
            this.dialog.message = '<div class="p-2 text-center text-white bg-red-600">You re about to delete this interview.<br><span class="has-text-weight-bold">Continue?</span></div>';
            this.dialog.confirmText = "YES delete Interview";
            this.dialog.onConfirm = () => {
                this.loading = true;
                this.message = "";
                let self = this;
                axios
                    .delete(
                        window.location.origin +
                        this.productionUrl +
                        "interview/" +
                        id,
                        {data: id}
                    )
                    .then((response) => {
                        setTimeout(function () {
                            self.loading = false;

                            window.location.reload();
                        }, 500);
                    })
                    .catch(function (error) {
                        self.loading = false;
                    });
            }
            this.dialog.onCancel = () => {
                this.dialog.show = false;
            };

        },
        getLocaleDateString: function () {
            var formats = {
                "ar-SA": "DD/MM/YY HH:mm",
                "bg-BG": "DD.M.YYYY HH:mm",
                "ca-ES": "DD/MM/YYYY HH:mm",
                "zh-TW": "YYYY/M/d HH:mm",
                "cs-CZ": "DD.M.YYYY HH:mm",
                "da-DK": "DD-MM-YYYY HH:mm",
                "de-DE": "DD.MM.YYYY HH:mm",
                "el-GR": "d/M/YYYY HH:mm",
                "en-US": "M/d/YYYY HH:mm",
                "fi-FI": "DD.M.YYYY HH:mm",
                "fr-FR": "DD/MM/YYYY HH:mm",
                "he-IL": "DD/MM/YYYY HH:mm",
                "hu-HU": "YYYY. MM. DD. HH:mm",
                "is-IS": "DD.M.YYYY HH:mm",
                "it-IT": "DD/MM/YYYY HH:mm",
                "ja-JP": "YYYY/MM/DD HH:mm",
                "ko-KR": "YYYY-MM-DD HH:mm",
                "nl-NL": "d-M-YYYY HH:mm",
                "nb-NO": "DD.MM.YYYY HH:mm",
                "pl-PL": "YYYY-MM-DD HH:mm",
                "pt-BR": "d/M/YYYY HH:mm",
                "ro-RO": "DD.MM.YYYY HH:mm",
                "ru-RU": "DD.MM.YYYY HH:mm",
                "hr-HR": "DD.M.YYYY HH:mm",
                "sk-SK": "DD. M. YYYY HH:mm",
                "sq-AL": "YYYY-MM-DD HH:mm",
                "sv-SE": "YYYY-MM-DD HH:mm",
                "th-TH": "DD/M/YYYY HH:mm",
                "tr-TR": "DD.MM.YYYY HH:mm",
                "ur-PK": "DD/MM/YYYY HH:mm",
                "id-ID": "DD/MM/YYYY HH:mm",
                "uk-UA": "DD.MM.YYYY HH:mm",
                "be-BY": "DD.MM.YYYY HH:mm",
                "sl-SI": "DD.M.YYYY HH:mm",
                "et-EE": "DD.MM.YYYY HH:mm",
                "lv-LV": "YYYY.MM.DD. HH:mm",
                "lt-LT": "YYYY.MM.DD HH:mm",
                "fa-IR": "MM/DD/YYYY HH:mm",
                "vi-VN": "DD/MM/YYYY HH:mm",
                "hy-AM": "DD.MM.YYYY HH:mm",
                "az-Latn-AZ": "DD.MM.YYYY HH:mm",
                "eu-ES": "YYYY/MM/DD HH:mm",
                "mk-MK": "DD.MM.YYYY HH:mm",
                "af-ZA": "YYYY/MM/DD HH:mm",
                "ka-GE": "DD.MM.YYYY HH:mm",
                "fo-FO": "DD-MM-YYYY HH:mm",
                "hi-IN": "DD-MM-YYYY HH:mm",
                "ms-MY": "DD/MM/YYYY HH:mm",
                "kk-KZ": "DD.MM.YYYY HH:mm",
                "ky-KG": "DD.MM.YY HH:mm",
                "sw-KE": "M/d/YYYY HH:mm",
                "uz-Latn-UZ": "DD/MM YYYY HH:mm",
                "tt-RU": "DD.MM.YYYY HH:mm",
                "pa-IN": "DD-MM-YY HH:mm",
                "gu-IN": "DD-MM-YY HH:mm",
                "ta-IN": "DD-MM-YYYY HH:mm",
                "te-IN": "DD-MM-YY HH:mm",
                "kn-IN": "DD-MM-YY HH:mm",
                "mr-IN": "DD-MM-YYYY HH:mm",
                "sa-IN": "DD-MM-YYYY HH:mm",
                "mn-MN": "YY.MM.DD HH:mm",
                "gl-ES": "DD/MM/YY HH:mm",
                "kok-IN": "DD-MM-YYYY HH:mm",
                "syr-SY": "DD/MM/YYYY HH:mm",
                "dv-MV": "DD/MM/YY HH:mm",
                "ar-IQ": "DD/MM/YYYY HH:mm",
                "zh-CN": "YYYY/M/d HH:mm",
                "de-CH": "DD.MM.YYYY HH:mm",
                "en-GB": "DD/MM/YYYY HH:mm",
                "es-MX": "DD/MM/YYYY HH:mm",
                "fr-BE": "d/MM/YYYY HH:mm",
                "it-CH": "DD.MM.YYYY HH:mm",
                "nl-BE": "d/MM/YYYY HH:mm",
                "nn-NO": "DD.MM.YYYY HH:mm",
                "pt-PT": "DD-MM-YYYY HH:mm",
                "sr-Latn-CS": "DD.M.YYYY HH:mm",
                "sv-FI": "DD.M.YYYY HH:mm",
                "az-Cyrl-AZ": "DD.MM.YYYY HH:mm",
                "ms-BN": "DD/MM/YYYY HH:mm",
                "uz-Cyrl-UZ": "DD.MM.YYYY HH:mm",
                "ar-EG": "DD/MM/YYYY HH:mm",
                "zh-HK": "d/M/YYYY HH:mm",
                "de-AT": "DD.MM.YYYY HH:mm",
                "en-AU": "d/MM/YYYY HH:mm",
                "es-ES": "DD/MM/YYYY HH:mm",
                "fr-CA": "YYYY-MM-DD HH:mm",
                "sr-Cyrl-CS": "DD.M.YYYY HH:mm",
                "ar-LY": "DD/MM/YYYY HH:mm",
                "zh-SG": "d/M/YYYY HH:mm",
                "de-LU": "DD.MM.YYYY HH:mm",
                "en-CA": "DD/MM/YYYY HH:mm",
                "es-GT": "DD/MM/YYYY HH:mm",
                "fr-CH": "DD.MM.YYYY HH:mm",
                "ar-DZ": "DD-MM-YYYY HH:mm",
                "zh-MO": "D/M/YYYY HH:mm",
                "de-LI": "DD.MM.YYYY HH:mm",
                "en-NZ": "d/MM/YYYY HH:mm",
                "es-CR": "DD/MM/YYYY HH:mm",
                "fr-LU": "DD/MM/YYYY HH:mm",
                "ar-MA": "DD-MM-YYYY HH:mm",
                "en-IE": "DD/MM/YYYY HH:mm",
                "es-PA": "MM/DD/YYYY HH:mm",
                "fr-MC": "DD/MM/YYYY HH:mm",
                "ar-TN": "DD-MM-YYYY HH:mm",
                "en-ZA": "YYYY/MM/DD HH:mm",
                "es-DO": "DD/MM/YYYY HH:mm",
                "ar-OM": "DD/MM/YYYY HH:mm",
                "en-JM": "DD/MM/YYYY HH:mm",
                "es-VE": "DD/MM/YYYY HH:mm",
                "ar-YE": "DD/MM/YYYY HH:mm",
                "en-029": "MM/DD/YYYY HH:mm",
                "es-CO": "DD/MM/YYYY HH:mm",
                "ar-SY": "DD/MM/YYYY HH:mm",
                "en-BZ": "DD/MM/YYYY HH:mm",
                "es-PE": "DD/MM/YYYY HH:mm",
                "ar-JO": "DD/MM/YYYY HH:mm",
                "en-TT": "DD/MM/YYYY HH:mm",
                "es-AR": "DD/MM/YYYY HH:mm",
                "ar-LB": "DD/MM/YYYY HH:mm",
                "en-ZW": "M/d/YYYY HH:mm",
                "es-EC": "DD/MM/YYYY HH:mm",
                "ar-KW": "DD/MM/YYYY HH:mm",
                "en-PH": "M/d/YYYY HH:mm",
                "es-CL": "DD-MM-YYYY HH:mm",
                "ar-AE": "DD/MM/YYYY HH:mm",
                "es-UY": "DD/MM/YYYY HH:mm",
                "ar-BH": "DD/MM/YYYY HH:mm",
                "es-PY": "DD/MM/YYYY HH:mm",
                "ar-QA": "DD/MM/YYYY HH:mm",
                "es-BO": "DD/MM/YYYY HH:mm",
                "es-SV": "DD/MM/YYYY HH:mm",
                "es-HN": "DD/MM/YYYY HH:mm",
                "es-NI": "DD/MM/YYYY HH:mm",
                "es-PR": "DD/MM/YYYY HH:mm",
                "am-ET": "d/M/YYYY HH:mm",
                "tzm-Latn-DZ": "DD-MM-YYYY HH:mm",
                "iu-Latn-CA": "d/MM/YYYY HH:mm",
                "sma-NO": "DD.MM.YYYY HH:mm",
                "mn-Mong-CN": "YYYY/M/d HH:mm",
                "gd-GB": "DD/MM/YYYY HH:mm",
                "en-MY": "d/M/YYYY HH:mm",
                "prs-AF": "DD/MM/YY HH:mm",
                "bn-BD": "DD-MM-YY HH:mm",
                "wo-SN": "DD/MM/YYYY HH:mm",
                "rw-RW": "M/d/YYYY HH:mm",
                "qut-GT": "DD/MM/YYYY HH:mm",
                "sah-RU": "MM.DD.YYYY HH:mm",
                "gsw-FR": "DD/MM/YYYY HH:mm",
                "co-FR": "DD/MM/YYYY HH:mm",
                "oc-FR": "DD/MM/YYYY HH:mm",
                "mi-NZ": "DD/MM/YYYY HH:mm",
                "ga-IE": "DD/MM/YYYY HH:mm",
                "se-SE": "YYYY-MM-DD HH:mm",
                "br-FR": "DD/MM/YYYY HH:mm",
                "smn-FI": "DD.M.YYYY HH:mm",
                "moh-CA": "M/d/YYYY HH:mm",
                "arn-CL": "DD-MM-YYYY HH:mm",
                "ii-CN": "YYYY/M/d HH:mm",
                "dsb-DE": "DD. M. YYYY HH:mm",
                "ig-NG": "d/M/YYYY HH:mm",
                "kl-GL": "DD-MM-YYYY HH:mm",
                "lb-LU": "DD/MM/YYYY HH:mm",
                "ba-RU": "DD.MM.YY HH:mm",
                "nso-ZA": "YYYY/MM/DD HH:mm",
                "quz-BO": "DD/MM/YYYY HH:mm",
                "yo-NG": "d/M/YYYY HH:mm",
                "ha-Latn-NG": "d/M/YYYY HH:mm",
                "fil-PH": "M/d/YYYY HH:mm",
                "ps-AF": "DD/MM/YY HH:mm",
                "fy-NL": "d-M-YYYY HH:mm",
                "ne-NP": "M/d/YYYY HH:mm",
                "se-NO": "DD.MM.YYYY HH:mm",
                "iu-Cans-CA": "d/M/YYYY HH:mm",
                "sr-Latn-RS": "DD.M.YYYY HH:mm",
                "si-LK": "YYYY-MM-DD HH:mm",
                "sr-Cyrl-RS": "DD.M.YYYY HH:mm",
                "lo-LA": "DD/MM/YYYY HH:mm",
                "km-KH": "YYYY-MM-DD HH:mm",
                "cy-GB": "DD/MM/YYYY HH:mm",
                "bo-CN": "YYYY/M/d HH:mm",
                "sms-FI": "DD.M.YYYY HH:mm",
                "as-IN": "DD-MM-YYYY HH:mm",
                "ml-IN": "DD-MM-YY HH:mm",
                "en-IN": "DD-MM-YYYY HH:mm",
                "or-IN": "DD-MM-YY HH:mm",
                "bn-IN": "DD-MM-YY HH:mm",
                "tk-TM": "DD.MM.YY HH:mm",
                "bs-Latn-BA": "DD.M.YYYY HH:mm",
                "mt-MT": "DD/MM/YYYY HH:mm",
                "sr-Cyrl-ME": "DD.M.YYYY HH:mm",
                "se-FI": "DD.M.YYYY HH:mm",
                "zu-ZA": "YYYY/MM/DD HH:mm",
                "xh-ZA": "YYYY/MM/DD HH:mm",
                "tn-ZA": "YYYY/MM/DD HH:mm",
                "hsb-DE": "DD. M. YYYY HH:mm",
                "bs-Cyrl-BA": "DD.M.YYYY HH:mm",
                "tg-Cyrl-TJ": "DD.MM.YY HH:mm",
                "sr-Latn-BA": "DD.M.YYYY HH:mm",
                "smj-NO": "DD.MM.YYYY HH:mm",
                "rm-CH": "DD/MM/YYYY HH:mm",
                "smj-SE": "YYYY-MM-DD HH:mm",
                "quz-EC": "DD/MM/YYYY HH:mm",
                "quz-PE": "DD/MM/YYYY HH:mm",
                "hr-BA": "DD.M.YYYY. HH:mm",
                "sr-Latn-ME": "DD.M.YYYY HH:mm",
                "sma-SE": "YYYY-MM-DD HH:mm",
                "en-SG": "d/M/YYYY HH:mm",
                "ug-CN": "YYYY-M-d HH:mm",
                "sr-Cyrl-BA": "DD.M.YYYY HH:mm",
                "es-US": "M/DD/YYYY HH:mm",
            };

            return formats[navigator.language] || "DD/MM/YYYY HH:mm";
        },
    },
};
</script>
