<template>
    <div
        class="py-4 mt-10 -mx-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 md:rounded-lg"
    >
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th
                        scope="col"
                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                    >
                        Url
                    </th>
                    <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                    >
                        {{ trans("Name") }}
                    </th>
                    <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                    >
                        {{ trans("First opened at") }}
                    </th>
                    <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell"
                    >
                        {{ trans("Submitted at") }}
                    </th>
                    <th
                        scope="col"
                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                    >
                        {{ trans("Url Valid until") }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(url, index) in urls" :key="index">
                    <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        {{ url.shorturl }}
                    </td>
                    <td
                        class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                    >
                        {{ url.url }}
                    </td>
                    <td
                        class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                    >
                        {{
                            url.first_opened_at
                                ? formatdate(url.first_opened_at)
                                : "never opened"
                        }}
                    </td>
                    <td
                        class="hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell"
                    >
                        {{
                            url.submitted_at
                                ? formatdate(url.submitted_at)
                                : "never submitted"
                        }}
                    </td>
                    <td class="px-3 py-3.5 text-sm text-gray-500">
                        {{
                            url.created_at
                                ? formatdate(url.created_at, true)
                                : "never created"
                        }}
                    </td>
                    <td
                        class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-sm font-medium"
                    >
                        <a href="#" @click="confirmdelete(url.id, url.url_id)">
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white bg-red-500 border border-gray-300 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                {{ trans("Delete") }}
                            </button>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Custom Toast Notification -->
        <div v-if="showToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
            {{ toastMessage }}
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: ["urls"],

    data() {
        return {
            moment: moment,
            sortIcon: "arrow-up",
            sortIconSize: "is-small",
            defaultSortDirection: "asc",
            showToast: false,
            toastMessage: "",
        };
    },
    methods: {
        formatdate: function (date, add = false) {
            var localeData = moment.localeData();
            var format =
                localeData.longDateFormat("L") +
                " " +
                localeData.longDateFormat("LT");

            return add
                ? moment(String(date))
                      .add(14, "days")
                      .format(this.getLocaleDateString())
                : moment(String(date)).format(this.getLocaleDateString());
        },
        confirmdelete: function (id, url_id) {
            if (confirm("You are about to delete this url. Continue?")) {
                this.deleteurl(id, url_id);
            }
        },
        deleteurl: function (id, url_id) {
            this.loading = true;
            this.message = "";
            let self = this;
            axios
                .delete(
                    window.location.origin +
                        this.productionUrl +
                        "/interview/publicurl/delete",
                    {
                        data: {
                            id: id,
                            url_id: url_id,
                        },
                    }
                )
                .then((response) => {
                    setTimeout(function () {
                        self.loading = false;
                        self.showToastMessage("Url deleted");

                        window.location.reload();
                    }, 500);
                })
                .catch(function (error) {
                    self.loading = false;
                    self.showToastMessage(
                        "There was an error during the request - refresh page and try again"
                    );
                });
        },
        showToastMessage: function (message) {
            this.toastMessage = message;
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 3000); // Hide toast after 3 seconds
        },
        getLocaleDateString: function () {
            var formats = {
                // Add your locale formats here
                "en-US": "M/d/YYYY HH:mm",
                // Other locale formats...
            };

            return formats[navigator.language] || "DD/MM/YYYY HH:mm";
        },
    },
};
</script>

<style scoped></style>
