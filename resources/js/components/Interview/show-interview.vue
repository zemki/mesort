<template>
    <div class="container mx-auto py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <button @click="goToStudyDetails()" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                {{ trans("Back") }}
            </button>
        </div>

        <!-- Project Details Section -->
        <section class="bg-white border-b border-gray-200 mb-8">
            <div class="px-6 py-8 text-center">
                <h2 class="text-base font-semibold tracking-wide text-blue-500 uppercase mb-2">{{
                        trans("Project")
                    }}</h2>
                <p class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl">{{ study.name }}</p>
                <p class="max-w-xl mx-auto my-4 text-xl text-gray-500">{{ study.description }}</p>
                <div class="text-base text-gray-700">
                    <p><span class="font-bold">{{ trans("Interview done by") }}:</span> {{ author }}</p>
                    <p><span class="font-bold">{{ trans("Interviewed") }}:</span> {{ interview.interviewed }}</p>
                    <p>
                        <span class="font-bold">{{ trans("From") }}:</span> {{ interviewStart }} -
                        <span class="font-bold">{{ trans("To") }}:</span> {{ interviewEnd }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Sorting Section -->
        <section class="bg-white border-b border-gray-200 mb-8">
            <div class="px-6 py-8">
                <h1 class="text-4xl font-bold uppercase text-gray-900">{{ trans("Sorting(s)") }}</h1>
                <sortings :screenshots="screenshots"></sortings>
            </div>
        </section>

        <!-- Created Tokens Section -->
        <section class="bg-white border-b border-gray-200 mb-8">
            <div class="px-6 py-8">
                <h2 class="text-2xl font-bold text-gray-900">{{ trans("Created Tokens") }}</h2>
                <div v-if="createdTokens.length">
                    <div v-for="token in createdTokens" :key="token.id" class="flex items-center mt-4">
                        <div class="flex-shrink-0 mr-4">
                            <img :src="token.image_path" alt="token created" class="w-16 h-16 rounded-lg"/>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">{{ token.name }}</h4>
                            <p class="mt-1 text-gray-600">{{ trans("Created in Sorting") }}: {{ token.sorting }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="mt-4">
                    <h2 class="text-xl font-bold text-gray-600">{{ trans("No Created Tokens") }}</h2>
                </div>
            </div>
        </section>

        <!-- Pre-Sort Phase Section -->
        <section class="bg-white border-b border-gray-200 mb-8">
            <div class="px-6 py-8">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900">{{ trans("Pre-Sort Phase") }}</h2>
                <dl class="space-y-6">
                    <div v-for="question in questions" :key="question.id">
                        <template v-if="question.detail.includes('presort')">
                            <dt class="text-2xl font-medium text-gray-900">{{ question.question }}</dt>
                            <dd class="mt-2">
                                <div v-for="answer in question.answers" :key="answer.id" class="mb-4">
                                    <p v-if="answer.answer.answer !== '' && answer.answer.type !== 'scale'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.answer.answer }}
                                    </p>
                                    <p v-else-if="answer.answer.type === 'open'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.pivot.result }}
                                    </p>
                                    <p v-else-if="answer.answer.type === 'scale'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.pivot.result }}
                                    </p>
                                    <p v-else
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-500">
                                        {{ trans("Answer not provided!") }}
                                    </p>
                                </div>
                                <h3 v-if="question.available_answers.length > 0"
                                    class="text-xl font-medium text-gray-900">{{ trans("Available Answers") }}</h3>
                                <div v-for="available in question.available_answers" :key="available.id" class="mt-2">
                                    <p v-if="available.type === 'multi' || available.type === 'onechoice'"
                                       class="text-base text-gray-800">{{ available.answer }}</p>
                                    <p v-else-if="available.type === 'scale'" class="text-base text-gray-600">
                                        {{ trans("Minimum value") }}: {{ available.answer.min }}<br>
                                        {{ trans("Max value") }}: {{ available.answer.max }}
                                    </p>
                                    <p v-else class="text-base text-gray-600">{{ trans("Open answer") }}</p>
                                </div>
                            </dd>
                        </template>
                    </div>
                </dl>
            </div>
        </section>

        <!-- Post-Sort Phase Section -->
        <section class="bg-white border-b border-gray-200 mb-8">
            <div class="px-6 py-8">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900">{{ trans("Post-Sort Phase") }}</h2>
                <dl class="space-y-6">
                    <div v-for="question in questions" :key="question.id">
                        <template v-if="question.detail.includes('postsort')">
                            <dt class="text-2xl font-medium text-gray-900">{{ question.question }}</dt>
                            <dd class="mt-2">
                                <div v-for="answer in question.answers" :key="answer.id" class="mb-4">
                                    <p v-if="answer.answer.answer !== '' && answer.answer.type !== 'scale'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.answer.answer }}
                                    </p>
                                    <p v-else-if="answer.answer.type === 'open'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.pivot.result }}
                                    </p>
                                    <p v-else-if="answer.answer.type === 'scale'"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-500">
                                        {{ answer.pivot.result }}
                                    </p>
                                    <p v-else
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-500">
                                        {{ trans("Answer not provided!") }}
                                    </p>
                                </div>
                                <h3 v-if="question.available_answers.length > 0"
                                    class="text-xl font-medium text-gray-900">{{ trans("Available Answers") }}</h3>
                                <div v-for="available in question.available_answers" :key="available.id" class="mt-2">
                                    <p v-if="available.type === 'multi' || available.type === 'onechoice'"
                                       class="text-base text-gray-800">{{ available.answer }}</p>
                                    <p v-else-if="available.type === 'scale'" class="text-base text-gray-600">
                                        {{ trans("Minimum value") }}: {{ available.answer.min }}<br>
                                        {{ trans("Max value") }}: {{ available.answer.max }}
                                    </p>
                                    <p v-else class="text-base text-gray-600">{{ trans("Open answer") }}</p>
                                </div>
                            </dd>
                        </template>
                    </div>
                </dl>
            </div>
        </section>
    </div>
</template>

<script>
import Sortings from "./view-sorting-interview.vue";

export default {
    components: {Sortings},
    props: {
        study: Object,
        author: String,
        interview: Object,
        screenshots: Array,
        createdTokens: Array,
        questions: Array,
        columnValues: Array,
        isQSortSorting: Boolean,
        extremeQuestion: Object,
    },
    data() {
        return {
            baseUrl: window.location.origin || ''
        }
    },
    computed: {
        interviewStart() {
            return new Date(this.interview.start).toLocaleString();
        },
        interviewEnd() {
            return new Date(this.interview.end).toLocaleString();
        }
    },
    methods: {
        goToStudyDetails() {
            window.location.href = `${this.baseUrl}${this.productionUrl}/studies/${this.study.id}`;
        },
    }
};
</script>

<style scoped>
.pb-4 {
    padding-bottom: 1rem;
}
</style>
