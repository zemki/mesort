<template>
    <section>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg max-w-full break-word">
            <thead class="bg-gray-50">
            <tr class="text-gray-600 text-left">
                <th class="px-6 py-4 text-sm font-semibold uppercase">ID</th>
                <th class="px-6 py-4 text-sm font-semibold uppercase">Author</th>
                <th class="px-6 py-4 text-sm font-semibold uppercase">Name</th>
                <th class="px-6 py-4 text-sm font-semibold uppercase">Description</th>
                <th class="px-6 py-4 text-sm font-semibold uppercase">Url</th>
                <th class="px-6 py-4 text-sm font-semibold uppercase text-center">Time</th>
            </tr>
            </thead>
            <tbody v-if="!isEmpty">
            <tr v-for="action in sortedActions" :key="action.id"
                :class="action.description.includes('delete') ? 'bg-red-600 text-gray-300 hover:bg-red-800' : 'bg-white text-black-300 hover:bg-gray-700 hover:text-white'">
                <td class="px-6 py-4 text-sm">{{ action.id }}</td>
                <td class="px-6 py-4 text-sm">{{ action.user.email }}</td>
                <td class="px-6 py-4 text-sm">{{ action.name }}</td>
                <td class="px-6 py-4 text-sm">{{ action.description }}</td>
                <td class="px-6 py-4 text-sm break-words max-w-xs">{{ action.url }}</td>
                <td class="px-6 py-4 text-sm text-center">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-600 rounded">
                            {{ new Date(action.time).toLocaleString() }}
                        </span>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No Actions.</td>
            </tr>
            </tbody>
        </table>
        <div v-if="paginatedActions.length > 1" class="mt-4">
            <button @click="previousPage" :disabled="currentPage === 1"
                    class="px-4 py-2 mr-2 text-sm text-white bg-blue-500 rounded disabled:opacity-50">Previous
            </button>
            <button @click="nextPage" :disabled="currentPage === paginatedActions.length"
                    class="px-4 py-2 text-sm text-white bg-blue-500 rounded disabled:opacity-50">Next
            </button>
        </div>
    </section>
</template>

<script>
export default {
    name: "actiontable",
    props: ['actions'],
    computed: {
        isEmpty() {
            return this.actions.length === 0;
        },
        sortedActions() {
            return this.actions.sort((a, b) => b.id - a.id); // Sort by ID descending
        },
        paginatedActions() {
            const pageSize = 5;
            const pages = [];
            for (let i = 0; i < this.sortedActions.length; i += pageSize) {
                pages.push(this.sortedActions.slice(i, i + pageSize));
            }
            return pages;
        },
        currentPageActions() {
            return this.paginatedActions[this.currentPage - 1] || [];
        }
    },
    data() {
        return {
            currentPage: 1,
        };
    },
    methods: {
        nextPage() {
            if (this.currentPage < this.paginatedActions.length) {
                this.currentPage += 1;
            }
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage -= 1;
            }
        }
    }
};
</script>

<style scoped>
ul li {
    list-style: none !important;
}
</style>
