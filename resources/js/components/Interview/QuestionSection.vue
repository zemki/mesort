<template>
     <section v-show="currentPage === page" class="p-4 overflow-y-scroll">
        <div class="w-full max-h-screen flex flex-col items-center justify-center">
            <transition-group :duration="{ enter: 300, leave: 100 }" name="fade">
                <!-- Use a unique key, e.g., x.id or x.qid instead of index -->
                <div v-for="(x, index) in questions" v-show="currentIndex == index"
                     :key="x.qid"
                     class="w-full max-w-2xl mx-auto text-center transition h-screen overflow-auto px-4">
                    <h1 class="w-full text-3xl md:text-5xl mb-8">{{ x.q }}</h1>
                    <div class="w-full text-center min-h-full overflow-y-scroll">
                        <div v-for="(a, answerindex) in x.answer" :key="answerindex" class="mb-8">
                            <!-- Open Answer -->
                            <div v-if="a.type == 'open'" class="mt-8">
                                <textarea v-model="results[x.answer.ids[answerindex]]"
                                          class="block w-full h-40 px-4 py-2 leading-normal bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:ring"
                                          placeholder=". . ." type="text">
                                </textarea>
                            </div>
                            <!-- Scale Answer -->
                            <div v-if="a.type == 'scale'" class="block text-center mt-8">
                                <div class="w-full">
                                    <input :id="'slider' + x.answer.ids[answerindex]"
                                           v-model="results[x.answer.ids[answerindex]]" :max="a.answer.max"
                                           :min="a.answer.min" type="range" step="1"
                                           class="w-full h-2 bg-blue-200 rounded-lg appearance-none"/>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <div class="px-2 py-1 text-white bg-blue-500 rounded">
                                        {{ a.answer.minlabel }}
                                    </div>
                                    <div :for="'slider' + x.id"
                                         class="px-2 py-1 text-white bg-blue-500 rounded">
                                        {{ results[x.answer.ids[answerindex]] }}
                                    </div>
                                    <div class="px-2 py-1 text-white bg-blue-500 rounded">
                                        {{ a.answer.maxlabel }}
                                    </div>
                                </div>
                            </div>

                            <!-- Multi Answer -->
                            <div v-if="a.type == 'multi'"
                                 class="mt-8 buttons-interview flex flex-wrap justify-center">
                                <button v-model="results[x.answer.ids[answerindex]]"
                                        :class="results.multi.map(function (e) { return e.id; }).indexOf(x.answer.ids[answerindex]) > -1 ? 'is-selected' : ''"
                                        class="px-4 py-2 m-2 text-lg md:text-xl font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        href="#"
                                        @click.prevent="manageMultipleButton(a.answer, x.answer.ids[answerindex])">
                                    {{ a.answer }}
                                </button>
                            </div>
                            <!-- OneChoice Answer -->
                            <div v-if="a.type == 'onechoice'"
                                 class="mt-8 buttons-interview flex flex-wrap justify-center">
                                <button v-model="results[x.answer.ids[answerindex]]"
                                        :class="results.onechoice.map(function (e) { return e.id; }).indexOf(x.answer.ids[answerindex]) > -1 ? 'is-selected' : ''"
                                        class="px-4 py-2 m-2 text-lg md:text-xl font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        href="#"
                                        @click.prevent="manageOneChoiceButton(a.answer, x.answer.ids[answerindex], x.qid)">
                                    {{ a.answer }}
                                </button>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="h-full flex pb-40" v-show="currentIndex > 0 || currentIndex < questions.length - 1">
                            <a v-show="currentIndex > 0" :disabled="currentIndex == 0"
                               class="inline-block px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer"
                               @click="$emit('updateCurrentIndex', currentIndex - 1)">
                                < {{ trans("Previous Question") }}
                            </a>
                            <a v-show="currentIndex < questions.length - 1"
                               :disabled="questions.length == currentIndex - 1"
                               class="ml-4 inline-block px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer"
                               @click="$emit('updateCurrentIndex', currentIndex + 1)">
                                {{ trans("Next Question") }} >
                            </a>
                        </div>

                    </div>
                </div>
            </transition-group>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        questions: Array,
        currentIndex: Number,
        results: Object,
        currentPage: Number,
        page: Number|String,
    },
    methods: {
        manageMultipleButton(answer, answerIndex) {
            this.$emit('manage-multiple', answer, answerIndex);
        },
        manageOneChoiceButton(answer, answerIndex, qid) {
            this.$emit('manage-one-choice', answer, answerIndex, qid);
        }
    }
};
</script>
