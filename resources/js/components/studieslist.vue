<template>
    <div>
        <!-- Leave Project Modal -->
        <Modal
            v-if="showLeaveProjectModal"
            title="Confirm Leave"
            :visible="showLeaveProjectModal"
            @confirm="detachUser"
            @cancel="closeLeaveProjectModal"
        >
            <p>{{ trans("Are you sure you want to leave this study?") }}</p>
        </Modal>

        <!-- Duplicate Project Modal -->
        <Modal
            v-if="showDuplicateProjectModal"
            title="Confirm Duplicate"
            :visible="showDuplicateProjectModal"
            @confirm="duplicatestudy"
            @cancel="closeDuplicateProjectModal"
        >
            {{ trans('Do you want to duplicate the project ') }} {{ duplicateProjectName }} ?
        </Modal>

        <!-- New Interview Modal -->
        <Modal
            v-if="showInterviewModal"
            title="Interviewee Name"
            :visible="showInterviewModal"
            @confirm="gotoInterview"
            @cancel="setIntervieweeName"
        >
            <input
                v-model="interviewee"
                type="name"
                name="name"
                id="name"
                class="block w-full p-2 border-gray-500 border-solid rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder=". . ."
                aria-describedby="name-description"
            />
            <p class="mt-2 text-sm text-gray-500" id="email-description">
                {{ trans("You can leave this field empty") }}
            </p>
        </Modal>

        <!-- New Public URL Modal -->
        <Modal
            v-if="showPublicUrlModal"
            title="Create a URL"
            :visible="showPublicUrlModal"
            @confirm="toggleModal"
            @cancel="toggleModal"
        >
            <div>
                <p>{{ trans("Create a new url for a public interview") }}</p>
                <p>{{ trans("The url won't be valid anymore as soon as the interview is submitted.") }}</p>
                <p class="font-bold">{{ trans("If the url is not used within 2 weeks, it will be deleted.") }}</p>

                <div class="mt-4">
                    <label for="intervieweename" class="block text-sm font-medium text-gray-700">
                        {{ trans("Interviewee Name") }}
                    </label>
                    <input
                        id="intervieweename"
                        class="block w-full px-4 py-2 mt-1 leading-normal bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring focus:border-blue-300"
                        type="text"
                        v-model="interview.name"
                    />
                </div>

                <div class="mt-4">
                    <label for="publicUrl" class="block text-sm font-medium text-gray-700">
                        {{ trans("Public Interview URL") }}
                    </label>
                    <input
                        id="publicUrl"
                        class="block w-full px-4 py-2 mt-1 leading-normal bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring focus:border-blue-300"
                        type="url"
                        v-model="interview.url"
                        @click="selectAndCopy"
                    />
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        type="button"
                        @click="createPublicUrl"
                        :disabled="isUrlFilled"
                        :class="[
                    'px-4 py-2 text-base font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2',
                    isUrlFilled ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
                ]"
                    >
                        Create Public URL
                    </button>
                </div>
            </div>
        </Modal>


        <!-- Delete Study Modal -->
        <Modal
            v-if="showDeleteStudyModal"
            title="Confirm Delete"
            :visible="showDeleteStudyModal"
            @confirm="deleteStudy"
            @cancel="closeDeleteStudyModal"
        >
            <div class="p-2 text-center text-white bg-red-600">
                <p>{{ trans("You are about to delete the study") }}</p>
                <p class="uppercase">{{ deleteStudyName }}</p>
                <p>{{ trans("and all its content?") }}</p>
                <p class="has-text-weight-bold">{{ trans("Continue?") }}</p>
            </div>
        </Modal>

        <!-- Snackbar -->
        <Snackbar :message="snackbarMessage" :duration="3000" ref="snackbar"/>
        <!--Modal-->
        <div
            class="fixed inset-0 z-50 hidden overflow-y-auto newPublicUrlModal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 hidden transition-opacity bg-gray-500 bg-opacity-75 newPublicUrlModal"
                    aria-hidden="true"
                ></div>
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                >&#8203;</span
                >

                <div
                    class="relative hidden inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl newPublicUrlModal sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                >
                    <div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900"
                                id="modal-title"
                            >
                                {{ trans("Create a URL") }}
                            </h3>
                        </div>
                    </div>
                    <div>
                        <div class="mt-1">
                            <p>
                                {{
                                    trans(
                                        "Create a new url for a public interview"
                                    )
                                }}
                            </p>
                            <p>
                                {{
                                    trans(
                                        "The url won't be valid anymore as soon as the interview is submitted."
                                    )
                                }}
                            </p>
                            <p class="font-bold">
                                {{
                                    trans(
                                        "If the url is not used within 2 weeks, it will be deleted."
                                    )
                                }}
                            </p>

                            <p class="font-bold text-center">
                                {{ trans("Interviewee Name") }}
                            </p>
                            <input
                                class="block w-full px-4 py-2 leading-normal bg-white rounded-lg appearance-none focus:outline-none focus:ring"
                                type="text"
                                v-model="interview.name"
                                id="intervieweename"
                            />

                            <input
                                class="block w-full px-4 py-2 leading-normal bg-white rounded-lg appearance-none focus:outline-none focus:ring"
                                type="url"
                                v-model="interview.url"
                                id="publicUrl"
                            />
                        </div>
                    </div>

                    <div
                        class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
                    >
                        <button
                            @click.prevent="toggleModal()"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0"
                        >
                            Cancel
                        </button>
                        <button
                            @click="createPublicUrl()"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0"
                        >
                            {{ trans("Create") }}
                        </button>
                        <button
                            @click="copyInterviewUrlToClipboard()"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0"
                        >
                            {{ trans("Copy to Clipboard") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 pb-4 pl-4 pr-6 sm:pl-6 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <h1 class="text-lg font-medium mr-4">{{ trans("Projects") }}</h1>
                    <a :href="route('studies.create')" :title="trans('Create a new Project')">
                        <button type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                            {{ trans('New Project') }}
                        </button>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <div v-if="invitesExists" class="flex items-center">
                        <input v-model="onlyInvitation" id="invites" aria-describedby="invites-description"
                               name="invites" type="checkbox"
                               class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500"/>
                        <label for="invites" class="ml-2 text-sm font-medium text-gray-700">Only Invitations</label>
                    </div>
                    <div class="w-64">
                        <label for="search-studies" class="sr-only">{{ trans("Search Studies") }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input type="search" id="search-studies" name="search-projects" v-model="search"
                                   autocomplete="off"
                                   class="block w-full py-2 pl-10 pr-3 leading-5 text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-blue-500 sm:text-sm"
                                   :placeholder="trans('Search Projects')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul role="list" class="relative divide-y divide-gray-200">
            <li
                class="relative py-6 pl-8 pr-6 hover:bg-gray-50 sm:pl-10 lg:pl-12 xl:pl-10"
                v-for="(study, index) in filteredList"
                :key="index"
            >
                <div class="flex items-start justify-between space-x-6">
                    <div class="min-w-0 flex-1 space-y-3">
                        <div>
                            <h2 class="text-3xl font-semibold text-gray-900">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ study.name }}
                            </h2>
                            <p class="text-sm text-gray-500 max-w-md mt-2">
                                {{ study.description }}
                            </p>
                        </div>
                        <div class="space-y-2">
                    <span class="text-sm font-medium text-gray-500 truncate group-hover:text-gray-900">
                        {{ study.sortings[0].name }}
                    </span>
                            <span
                                class="block mt-1 text-sm font-medium text-gray-500 truncate group-hover:text-gray-900">
                        {{ study.interviews.length }} {{ trans("Interviews") }}
                    </span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-gray-700">
                                {{ trans("Responsible Researcher(s)") }}
                            </p>
                            <pre class="max-w-sm text-sm text-gray-500 break-words whitespace-normal"
                                 v-html="study.author"></pre>
                            <span
                                v-if="!study.authiscreator"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-700"
                            >
                        <svg
                            class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-500"
                            fill="currentColor"
                            viewBox="0 0 8 8"
                        >
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        {{ trans("Invited By") }} {{ study.owner }}
                    </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-3 flex-shrink-0 sm:flex z-40">
                        <div class="space-y-2 w-full">
                            <a
                                title="manage study"
                                :href="productionUrl + '/studies/' + study.id"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-blue-700 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                {{ trans("Manage Project") }}
                            </a>
                            <a
                                title="new interview"
                                :href="productionUrl + '/interview/new?study=' + study.id"
                                @click.prevent="setIntervieweeName(study.id)"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-500
                            border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-blue-700
                            hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2
                            focus:ring-blue-500"
                            >
                                {{ trans("New Interview") }}
                            </a>
                            <a
                                href="#"
                                @click="toggleModal(study.id)"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                {{ trans("New Public Url") }}
                            </a>
                            <a
                                v-if="study.authiscreator"
                                href="#"
                                @click="confirmDelete(study.id, study.name)"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md shadow-sm hover:bg-red-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                {{ trans("Delete Project") }}
                            </a>
                            <a
                                v-if="study.authiscreator"
                                href="#"
                                @click="confirmduplicate(study.id, study.name)"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                {{ trans("Duplicate Project") }}
                            </a>
                            <a
                                v-if="!study.authiscreator"
                                href="#"
                                @click="confirmLeaveProject(loggedUser, study.id)"
                                class="block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md shadow-sm hover:bg-red-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                {{ trans("Leave Project") }}
                            </a>
                            <a
                                v-if="study.authiscreator"
                                :aria-disabled="!study.editable"
                                :href="productionUrl + '/studies/' + study.id + '/edit'"
                                title="edit study"
                                :class="!study.editable ? 'pointer-events-none select-none cursor-not-allowed opacity-50 block text-center w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500' : 'block text-center w-full px-3 py-1.5 text-sm font-medium hover:text-gray-200 text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'"
                            >
                                {{ trans("Edit Project") }}
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import Snackbar from "./snackbar.vue";
import Modal from "./modal.vue";

export default {
    components: {Modal, Snackbar},
    props: ["studies", "user"],
    computed: {
        isUrlFilled() {
            return !!this.interview.url; // Returns true if the URL is filled
        },
        filteredList() {
            return JSON.parse(this.studies).filter((study) => {
                if (this.onlyInvitation) {
                    return (
                        study.name.toLowerCase().includes(this.search.toLowerCase()) &&
                        !study.authiscreator
                    );
                } else {
                    return study.name.toLowerCase().includes(this.search.toLowerCase());
                }
            });
        },
        invitesExists() {
            return this.filteredList.filter(s => !s.authiscreator).length > 0;
        }

    },
    data() {
        return {
            search: "",
            loggedUser: JSON.parse(this.user),
            interview: {
                interviewed: "",
                study: "",
                url: "",
            },
            onlyInvitation: false,
            interviewee: "",
            studyid: 0,
            showLeaveProjectModal: false,
            showDuplicateProjectModal: false,
            snackbarMessage: "",
            leaveProjectStudyId: null,
            leaveProjectUserId: null,
            duplicateProjectId: null,
            duplicateProjectName: "",
            showPublicUrlModal: false,
            showDeleteStudyModal: false,
            deleteStudyName: "",
            showInterviewModal: false,
        };
    },
    created() {
        this.interviewee = "";
        this.studyid = 0;
    },
    mounted() {
        this.checkSnackbarMessage();
    },
    methods: {
        selectAndCopy(event) {
            const input = event.target;
            input.select();

            try {
                document.execCommand('copy');
                this.showSnackbarMessage("Copied to clipboard");

            } catch (err) {
                console.error('Failed to copy text', err);
            }
        },
        checkSnackbarMessage() {
            const message = localStorage.getItem("snackbarMessage");
            if (message) {
                this.showSnackbarMessage(message);
                localStorage.removeItem("snackbarMessage");
            }
        },
        confirmLeaveProject: function (userToDetach, studyId) {
            this.leaveProjectStudyId = studyId;
            this.leaveProjectUserId = userToDetach.id;
            this.showLeaveProjectModal = true;
        },
        detachUser: function () {
            let self = this;
            window.axios
                .post(
                    window.location.origin +
                    self.productionUrl +
                    "/studies/invite/" +
                    self.leaveProjectUserId,
                    {
                        email: self.loggedUser.email,
                        study: self.leaveProjectStudyId,
                    }
                )
                .then((response) => {
                    self.showSnackbarMessage(response.data.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                })
                .catch(function (error) {
                    self.showSnackbarMessage(
                        "There was an error during the request - refresh page and try again"
                    );
                });
        },
        closeLeaveProjectModal() {
            this.showLeaveProjectModal = false;
            this.leaveProjectStudyId = null;
            this.leaveProjectUserId = null;
        },
        confirmduplicate: function (id, name) {
            this.duplicateProjectId = id;
            this.duplicateProjectName = name;
            this.showDuplicateProjectModal = true;
        },
        duplicatestudy: function () {
            this.loading = true;
            this.message = "";
            let self = this;
            axios
                .get("studies/" + self.duplicateProjectId + "/duplicate")
                .then((response) => {
                    setTimeout(function () {
                        self.loading = false;
                        localStorage.setItem("snackbarMessage", self.trans("Project duplicated"));

                        window.location.reload();
                    }, 500);
                })
                .catch(function (error) {
                    console.log(error);
                    self.loading = false;
                    self.showSnackbarMessage(
                        "There was an error during the request - refresh page and try again"
                    );
                });
        },
        closeDuplicateProjectModal() {
            this.showDuplicateProjectModal = false;
            this.duplicateProjectId = null;
            this.duplicateProjectName = "";
        },
        confirmDelete: function (id, name) {
            this.deleteStudyId = id;
            this.deleteStudyName = name;
            this.showDeleteStudyModal = true;
        },
        deleteStudy: function () {
            this.loading = true;
            this.message = "";
            let self = this;
            axios
                .delete("studies/" + self.deleteStudyId, {data: self.deleteStudyId})
                .then((response) => {
                    setTimeout(function () {
                        self.loading = false;
                        localStorage.setItem("snackbarMessage", self.trans("Project deleted"));
                        window.location.reload();
                    }, 500);
                })
                .catch(function (error) {
                    self.loading = false;
                    self.showSnackbarMessage(error.response.data);
                });
        },
        closeDeleteStudyModal() {
            this.showDeleteStudyModal = false;
            this.deleteStudyId = null;
            this.deleteStudyName = "";
        },
        showSnackbarMessage(message) {
            this.snackbarMessage = message;
            this.$refs.snackbar.show();
        },
        gotoInterview: function () {
            this.interview.interviewed = this.interviewee;
            window.location.href =
                "interviews/new?study=" +
                this.studyid +
                "&interviewed=" +
                this.interviewee;
        },
        setIntervieweeName: function (studyid = 0) {
            if (studyid === 0) {
                this.interviewee = "";
                this.studyid = 0;
                this.showInterviewModal = false;
            } else {
                this.studyid = studyid;
                this.showInterviewModal = true;
            }
        },
        toggleModal(id = "") {
            this.showPublicUrlModal = !this.showPublicUrlModal;

            if (id != "" &&
                id != this.interview.study
            ) {
                this.interview.url = "";
                this.$forceUpdate();
            }
            if (id != "") {
                this.interview.study = id;
            }
        },
        createPublicUrl() {
            let self = this;
            axios
                .post("interview/publicurl/create",
                    {
                        study: this.interview.study,
                        name:
                        this.interview.name,
                    }
                )
                .then((response) => {
                    self.showSnackbarMessage(response.data.message);
                    self.interview.url = response.data.url;
                    self.$forceUpdate();
                })
                .catch(function (error) {
                    self.showSnackbarMessage(
                        "There was an error during the request - refresh page and try again"
                    );
                });
        },
    },
};
</script>
