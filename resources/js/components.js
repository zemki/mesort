import Vue from "vue";
import Gravatar from "vue-gravatar";

// Import components
import NewInterview from "./components/Interview/newinterview.vue";
import CircleSorting from "./components/Interview/circle-sorting.vue";
import NewToken from "./components/Interview/newtokenmodal.vue";
import InterviewList from "./components/Interview/interviewlist.vue";
import QSort from "./components/Interview/q-sort.vue";
import NetworkSorting from "./components/Interview/network-sorting.vue";
import SortingPreview from "./components/Interview/sorting_preview.vue";
import PublicUrlList from "./components/publicurllist.vue";
import NewStudy from "./components/newstudy.vue";
import ActionTable from "./components/actiontable.vue";
import UserTable from "./components/usertable.vue";
import BetterBrowserMessage from "./components/useChromeFirefoxModal.vue";
import StudyInvites from "./components/studyInvites.vue";
import StudiesList from "./components/studieslist.vue";
import Sortings from "./components/Interview/view-sorting-interview.vue";
import CustomDialogue from "./components/CustomDialogue.vue";
import ShowInterview from "./components/Interview/show-interview.vue";


// Register components globally
Vue.component("v-gravatar", Gravatar);
Vue.component("new-interview", NewInterview);
Vue.component("circle-sorting", CircleSorting);
Vue.component("new-token", NewToken);
Vue.component("interview-list", InterviewList);
Vue.component("q-sort", QSort);
Vue.component("network-sorting", NetworkSorting);
Vue.component("sorting-preview", SortingPreview);
Vue.component("url-list", PublicUrlList);
Vue.component("new-study", NewStudy);
Vue.component("action-table", ActionTable);
Vue.component("user-table", UserTable);
Vue.component("better-browser-message", BetterBrowserMessage);
Vue.component("study-invites", StudyInvites);
Vue.component("studies-list", StudiesList);
Vue.component("sortings", Sortings);
Vue.component("custom-dialogue", CustomDialogue);
Vue.component("show-interview", ShowInterview);
