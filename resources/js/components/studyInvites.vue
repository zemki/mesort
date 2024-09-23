<template>
    <section class="max-w-3xl mx-auto sm:px-6 lg:max-w-7xl lg:px-8" :key="studiesInvite">
        <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
            <div class="divide-y divide-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">
                        {{
                            trans("Enter an email to invite a researcher to work with in this study, then press enter")
                        }}
                    </h2>
                </div>
                <div class="px-4 py-6 sm:px-6">
                    <ul role="list" class="space-y-8">
                        <li v-for="(user, index) in invitedlist" :key="index">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <Gravatar class="w-8 h-8 rounded-full" :email="user.email"/>
                                </div>
                                <div>
                                    <div class="text-sm">
                                        <a href="#" class="font-medium text-gray-900">{{ user.email }}</a>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-700">
                                        <button
                                            class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700"
                                            @click="confirmdelete(user)">
                                            {{ trans("Delete Invite") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="px-4 py-4 sm:px-6 flex items-center space-x-2">
                    <input
                        id="invitee"
                        name="invitee"
                        class="block w-full py-2 pl-3 pr-10 leading-5 placeholder-gray-500 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 sm:text-sm"
                        :placeholder="trans('Enter email and press Enter or click Invite')"
                        v-model="toInvite"
                        autocomplete="off"
                        @keydown.enter.prevent="invite"
                    />
                    <button
                        :disabled="loading"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        @click="invite"
                    >
                        <span v-if="loading">Loading...</span>
                        <span v-else>{{ trans("Invite") }}</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Snackbar for messages -->
        <Snackbar v-if="showSnackbar" :message="snackbarMessage" :duration="3000" @show="showSnackbar = false"/>
    </section>
</template>

<script>
import Gravatar from "vue-gravatar";
import Snackbar from "./snackbar.vue";

export default {
    name: "studiesInvites",
    props: ["invitedlist", "isowner", "study"],
    components: {
        Gravatar,
        Snackbar,
    },
    data() {
        return {
            toInvite: "",
            studiesInvite: 0,
            showSnackbar: false,
            snackbarMessage: "",
            loading: false,
        };
    },
    methods: {
        forceRerender() {
            this.studiesInvite += 1;
        },
        invite: function () {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(this.toInvite)) {
                this.showSnackbarMessage("Invalid email format");
                return;
            }

            this.loading = true;

            let self = this;
            window.axios
                .post(
                    window.location.origin +
                    this.productionUrl +
                    "studies/invite",
                    {
                        email: this.toInvite,
                        study: this.study,
                    }
                )
                .then((response) => {
                    this.loading = false;
                    this.showSnackbarMessage(response.data.message);

                    if (
                        response.data.user &&
                        !self.invitedlist.some(invite => invite.email === response.data.user.email)
                    ) {
                        self.invitedlist.push(response.data.user);
                    }

                    this.forceRerender();
                })
                .catch((error) => {
                    this.loading = false;
                    if (error.message) {
                        this.showSnackbarMessage(error.message);
                    } else {
                        this.showSnackbarMessage(error.response.data);
                    }
                });
        },
        confirmdelete: function (userToDetach) {
            if (confirm(this.trans("Are you sure you want to remove the invite for this user?"))) {
                this.detachUser(userToDetach);
            }
        },
        detachUser: function (userToDetach) {
            let self = this;
            window.axios
                .post(
                    window.location.origin +
                    this.productionUrl +
                    "studies/invite/" +
                    userToDetach.id,
                    {email: userToDetach.email, study: this.study}
                )
                .then((response) => {
                    this.showSnackbarMessage(response.data.message);

                    self.invitedlist = self.invitedlist.filter(
                        user => user.email !== userToDetach.email
                    );
                    this.forceRerender();
                })
                .catch(function (error) {
                    this.showSnackbarMessage(
                        this.trans("There was an error during the request - refresh page and try again")
                    );
                });
        },
        showSnackbarMessage(message) {
            this.snackbarMessage = message;
            this.showSnackbar = true;
        },
    },
};
</script>


<style scoped></style>
