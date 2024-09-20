<template>
    <section>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-4 text-sm font-semibold text-left text-gray-600">ID</th>
                <th class="px-6 py-4 text-sm font-semibold text-left text-gray-600">Email</th>
                <th class="px-6 py-4 text-sm font-semibold text-left text-gray-600"># of Interviews</th>
                <th class="px-6 py-4 text-sm font-semibold text-left text-gray-600"># of Studies</th>
                <th class="px-6 py-4 text-sm font-semibold text-center text-gray-600">Last logged in</th>
                <th class="px-6 py-4 text-sm font-semibold text-center text-gray-600">Actions</th>
            </tr>
            </thead>
            <tbody v-if="!isEmpty">
            <tr v-for="user in users" :key="user.id" class="bg-white text-black-300 hover:bg-gray-700 hover:text-white">
                <td class="px-6 py-4 text-sm">{{ user.id }}</td>
                <td class="px-6 py-4 text-sm">{{ user.email }}</td>
                <td class="px-6 py-4 text-sm">{{ user.interviews.length }}</td>
                <td class="px-6 py-4 text-sm">{{ user.studies.length }}</td>
                <td class="px-6 py-4 text-sm text-center">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-600 rounded">
                            {{ user.last_login_date ? new Date(user.last_login_date).toLocaleString() : '' }}
                        </span>
                </td>
                <td class="px-6 py-4 text-sm text-center">
                    <button @click="confirmDeleteAllStudies(user.id)"
                            class="inline-flex items-center px-4 py-2 text-xs font-bold text-black bg-red-300 rounded hover:bg-red-400">
                        <svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M6 2a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 3h6v1H7V5zm1 3h4v8H8V8z"/>
                        </svg>
                        Delete all studies
                    </button>
                    <button @click="confirmCreateStudy(user.id)"
                            class="inline-flex items-center px-4 py-2 mt-2 text-xs font-bold text-gray-800 bg-green-300 rounded hover:bg-gray-400">
                        <svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2 2a2 2 0 012-2h12a2 2 0 012 2v16a2 2 0 01-2 2H4a2 2 0 01-2-2V2zm7 3v3H5v2h4v3l5-4-5-4z"/>
                        </svg>
                        Create Study for user
                    </button>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No Actions.</td>
            </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
export default {
    name: "usertable",
    props: ['users'],
    computed: {
        isEmpty() {
            return this.users.length === 0;
        }
    },
    methods: {
        confirmCreateStudy(id) {
            if (confirm("Do you want to create a dummy study for this user?")) {
                this.createStudy(id);
            }
        },
        createStudy(id) {
            this.loading = true;
            axios.post('createdummystudy/' + id)
                .then(response => {
                    this.loading = false;
                    alert("Study created");
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                    alert("There was an error during the request - refresh the page and try again");
                });
        },
        confirmDeleteAllStudies(id) {
            if (confirm("You are about to delete all the studies for this user. Continue?")) {
                this.deleteAllStudies(id);
            }
        },
        deleteAllStudies(id) {
            this.loading = true;
            axios.post('deletestudiesbyuser/' + id)
                .then(response => {
                    this.loading = false;
                    alert("Data deleted");
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                    alert("There was an error during the request - refresh the page and try again");
                });
        }
    }
}
</script>

<style scoped>
/* Add any additional styles if necessary */
</style>
