<template>

    <div
        id="newStudyContainer"
        class="flex flex-wrap w-2/3 mx-auto">
        <!-- Snackbar -->
        <Snackbar v-if="showSnackbar" :message="snackbarMessage" ref="snackbar"/>

        <sorting-preview
            ref="sorting-preview"
            :centerLabel="sorting.centerLabel"
            :circles="sorting.numberofcircles"
            :sections="sorting.sections"
            :sortingid="selectedSorting"
            :sortingdetails="shortDescription"
        ></sorting-preview>

        <form
            enctype="multipart/form-data">

            <h1 class="text-4xl font-extrabold tracking-tight text-blue-500 uppercase md:text-5xl lg:text-6xl"
                v-text="isedit ? trans('Edit Project') : trans('Create Project')">
            </h1>
            <h6 class="my-6 text-xl text-red-600">
                {{
                    trans('To create a new project, you should provide some general information, choose a sorting scheme you wish to use and add additional questions for your interviewees. To navigate between these options please use the three tabs provided below.')
                }}</h6>
            <div class="w-full p-4 rounded-md bg-yellow-50" v-show="response.length > 0">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Heroicon name: solid/exclamation -->
                        <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm text-yellow-800 font-base">{{ trans("Attention needed") }}</h3>
                        <div class="mt-2 text-yellow-700 text-medium">
                            <p v-html="response"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="w-full mx-auto ">
                <nav aria-label="Progress">
                    <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                        <li class="md:flex-1">
                            <a href="#"
                               class="flex flex-col py-2 pl-4 border-l-4 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
                               v-on:click="toggleTabs(1)"
                               :class="{'border-gray-200 group hover:border-gray-300': openTab !== 1, 'border-blue-500 group hover:border-blue-800': openTab === 1}">
        <span
            class="text-xs font-semibold tracking-wide uppercase"
            :class="{'text-gray-500 group-hover:text-gray-700 ': openTab !== 1, 'text-blue-500 group-hover:text-blue-800': openTab === 1}"
        >Step 1</span>
                                <span class="text-sm font-medium">
                            {{
                                        trans('Project Details')
                                    }}

        </span>
                            </a>
                        </li>

                        <li class="md:flex-1">
                            <!-- Current Step -->
                            <a href="#"
                               class="flex flex-col py-2 pl-4 border-l-4 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
                               :class="{'border-gray-200 group hover:border-gray-300': openTab !== 2, 'border-blue-500 group hover:border-blue-800': openTab === 2}"
                               v-on:click="toggleTabs(2)"
                            >
        <span
            class="text-xs font-semibold tracking-wide uppercase"
            :class="{'text-gray-500 group-hover:text-gray-700': openTab !== 2, 'text-blue-500 group-hover:text-blue-800': openTab === 2}"
        >Step 2</span>
                                <span class="text-sm font-medium">
                                      {{
                                        trans('Select Sorting Options')
                                    }}
        </span>
                            </a>
                        </li>

                        <li class="md:flex-1">
                            <!-- Upcoming Step -->
                            <a href="#"
                               class="flex flex-col py-2 pl-4 border-l-4 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
                               :class="{'border-gray-200 group hover:border-gray-300': openTab !== 3, 'border-blue-500 group hover:border-blue-800': openTab === 3}"
                               v-on:click="toggleTabs(3)">
        <span
            class="text-xs font-semibold tracking-wide uppercase"
            :class="{'text-gray-500 group-hover:text-gray-700': openTab !== 3, 'text-blue-500 group-hover:text-blue-800': openTab === 3}"
        >Step 3</span>
                                <span class="text-sm font-medium">
                                  {{
                                        trans('Questions')
                                    }}

        </span>
                            </a>
                        </li>
                    </ol>
                </nav>

                <div
                    class="relative flex flex-col w-full min-w-0 mb-6 break-words bg-white ">
                    <div
                        class="flex-auto px-4 py-5">
                        <div
                            class="tab-content tab-space">
                            <div :class="{'hidden': openTab !== 1, 'block': openTab === 1}">
                                <h6 class="mt-6 mb-4 subtitle has-text-danger">
                                    {{
                                        trans('Please give your project a name, assign one or more responsible researchers and add a short description. All information is mandatory to successful save the project.')
                                    }}
                                </h6>
                                <div class=" space-y-6">
                                    <div class="">
                                        <div class="field">
                                            <label
                                                :class="response.indexOf(errormessages.studyname) > -1 ? 'is-red text-uppercase': 'text-uppercase'"
                                                class="block text-base font-bold text-gray-700">
                                                {{
                                                    trans('Project Name')
                                                }}
                                            </label>
                                            <div class="w-full control">
                    <textarea
                        id="studyname"
                        v-model="name"
                        :class="response.indexOf(errormessages.studyname) > -1 ? 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm rounded-md border-red-500': 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-200 rounded-md'"
                        :maxlength="inputLength.studyname"
                        class="w-full textarea mt-2"
                        placeholder=""
                        row="1"
                        style="resize: none;white-space: nowrap;overflow-x: scroll;"
                        type="text">
                    </textarea>
                                                <span
                                                    :class="inputLength.studyname <= name.length ? 'text-red-600 text-xs w-auto inline-flex float-right mt-1' : 'text-xs text-gray-500 w-auto inline-flex float-right mt-1'">
                        {{ inputLength.studyname - name.length }}/{{ inputLength.studyname }}
                    </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="field">
                                            <label class="block text-base font-bold text-gray-700">
                                                {{ trans('Responsible Researcher(s)') }}
                                            </label>
                                            <div class="control">
                    <textarea
                        v-model="author"
                        :class="response.indexOf(errormessages.author) > -1 ? 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm rounded-md border-red-500': 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-200 rounded-md'"
                        :maxlength="inputLength.author"
                        class="textarea mt-2"
                        placeholder=""
                        type="text">
                    </textarea>
                                                <span
                                                    :class="inputLength.author <= author.length ? 'text-red-600 text-xs w-auto inline-flex float-right mt-1' : 'text-xs text-gray-500 w-auto inline-flex float-right mt-1'">
                        {{ inputLength.author - author.length }}/{{ inputLength.author }}
                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class=" mt-6">
                                    <div class="">
                                        <div class="field">
                                            <label class="block text-base font-bold text-gray-700">
                                                {{ trans('Short description of project') }}
                                            </label>
                                            <div class="control">
                    <textarea
                        v-model="shortDescription"
                        :class="response.indexOf(errormessages.short_description) > -1 ? 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm rounded-md border-red-500': 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-200 rounded-md'"
                        :maxlength="inputLength.short_description"
                        class="textarea mt-2"
                        placeholder="">
                    </textarea>
                                                <span
                                                    :class="inputLength.short_description <= shortDescription.length ? 'text-red-600 text-xs w-auto inline-flex float-right mt-1' : 'text-xs text-gray-500 w-auto inline-flex float-right mt-1'">
                        {{ inputLength.short_description - shortDescription.length }}/{{
                                                        inputLength.short_description
                                                    }}
                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                :class="{'hidden': openTab !== 2, 'block': openTab === 2}">
                                <h6 class="mt-2 subtitle has-text-danger">
                                    {{
                                        trans('Please provide your project with instructions for the interviewee, select a sorting scheme and define sorting tokens according to your needs, or use preset tokens.')
                                    }}
                                </h6>
                                <div
                                    class="">
                                    <div
                                        class="">
                                        <div
                                            class="field">
                                            <label
                                                class="label">
                                                {{
                                                    trans('Text displayed for the interviewees, e.g. sorting instructions')
                                                }}
                                            </label>
                                            <div
                                                class="w-full control">

                                <textarea
                                    id="sortingDescription"
                                    v-model="sorting.description"
                                    :maxlength="inputLength.sorting_description"
                                    class="block w-full border-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder=""
                                    type="text"></textarea>
                                                <span
                                                    :class="inputLength.sorting_description <= sorting.description.length ? 'text-red-600 text-xs w-auto inline-flex float-right' : 'text-xs text-gray-500 w-auto inline-flex float-right'">{{
                                                        inputLength.sorting_description - sorting.description.length
                                                    }}/{{
                                                        inputLength.sorting_description
                                                    }}
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex w-full h-auto">
                                    <div
                                        class="w-1/3">
                                        <label
                                            class="label">
                                            {{
                                                trans('Choose Sorting Scheme')
                                            }}
                                        </label>
                                        <div>
                                            <label id="listbox-label" class="sr-only">Choose Sorting</label>
                                            <div class="relative">
                                                <div class="inline-flex rounded-md shadow-sm">
                                                    <div
                                                        class="relative inline-flex items-center p-2 bg-blue-500 text-white rounded-l-md">
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                        <p class="ml-2 text-sm font-medium"
                                                           v-html="sortingTypes[selectedSorting-1]"></p>
                                                    </div>
                                                    <button @click="showSelectSorting = !showSelectSorting"
                                                            type="button"
                                                            class="inline-flex items-center p-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            aria-haspopup="listbox" aria-expanded="true"
                                                            aria-labelledby="listbox-label">
                                                        <span class="sr-only">Choose Sorting</span>
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <ul v-show="showSelectSorting"
                                                    class="absolute right-0 z-10 mt-2 w-72 bg-white shadow-lg rounded-md overflow-hidden ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                    tabindex="-1" role="listbox" aria-labelledby="listbox-label"
                                                    aria-activedescendant="listbox-option-0">
                                                    <li v-for="(sortingType, sindex) in sortingTypes" :key="sindex"
                                                        @click="hideAndAssignSorting(sindex)"
                                                        :id="'listbox-option-' + sindex"
                                                        class="relative p-4 cursor-pointer hover:bg-blue-100 focus:bg-blue-100">
                                                        <div class="flex items-center">
                                                            <div class="flex-1">
                                                                <p class="text-sm font-semibold text-gray-900"
                                                                   v-html="sortingType"></p>
                                                                <p class="mt-1 text-sm text-gray-500">
                                                                    {{ sortingsDescription[sindex] }}</p>
                                                            </div>
                                                            <img
                                                                :src="`${baseUrl}${productionUrl}images/sorting${sindex + 1}.png`"
                                                                alt="circle sorting"
                                                                class="z-10 bg-gray-100 rounded-full w-10 h-10 transition-transform duration-200 ease-in-out transform hover:scale-150 scale">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>


                                        <div
                                            v-if="selectedSorting !== 3"
                                            class="w-full">
                                            <div
                                                class="custom-number-input" v-if="selectedSorting === 2">
                                                <label
                                                    class="w-full text-sm font-semibold text-gray-700">{{
                                                        trans('Number of Sections') + ' (0-6)'
                                                    }}
                                                </label>
                                                <div
                                                    class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.sectionNumber >= 1) ? sorting.sectionNumber-- : sorting.sectionNumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                    </button>
                                                    <input
                                                        id="numberOfSections"
                                                        v-model="sorting.sectionNumber"
                                                        class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                        max="8"
                                                        min="0"
                                                        name="number-of-sections"
                                                        type="number"
                                                        value="0"></input>
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.sectionNumber <= 5) ? sorting.sectionNumber++ : sorting.sectionNumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div
                                                class="custom-number-input" v-if="selectedSorting === 1">
                                                <label
                                                    class="w-full text-sm font-semibold text-gray-700">{{
                                                        trans('Number of tokens to be sorted')
                                                    }}
                                                </label>
                                                <div
                                                    class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.tokennumber >= 1) ? sorting.tokennumber-- : sorting.tokennumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                    </button>
                                                    <input
                                                        v-model="sorting.tokennumber"
                                                        class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                        max="40"
                                                        min="0"
                                                        name="number-of-circles"
                                                        type="number"
                                                        value="0"/>
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.tokennumber <= 39) ? sorting.tokennumber++ : sorting.tokennumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-2 custom-number-input">
                                                <label
                                                    class="w-full text-sm font-semibold text-gray-700">{{
                                                        trans('Number of circles (2-8)')
                                                    }}
                                                </label>
                                                <div
                                                    class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.numberofcircles >= 3) ? sorting.numberofcircles-- : sorting.numberofcircles">
                                                        <span
                                                            class="m-auto text-2xl font-thin">−</span>
                                                    </button>
                                                    <input
                                                        v-model="sorting.numberofcircles"
                                                        class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                        max="8"
                                                        min="2"
                                                        name="number-of-circles"
                                                        type="number"
                                                        value="0"></input>
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(sorting.numberofcircles <= 7) ? sorting.numberofcircles++ : sorting.numberofcircles">
                                                        <span
                                                            class="m-auto text-2xl font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="selectedSorting == 3"
                                         class="flex flex-wrap w-1/2 ml-2 sm:-mx-px">

                                        <div class="w-1/2 overflow-hidden sm:my-px sm:px-px">
                                            <label
                                                class="w-full text-sm font-semibold text-gray-700">{{
                                                    trans('Base') + ' (2-10)'
                                                }}
                                            </label>
                                            <div
                                                class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg shrink">
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(sorting.qsortNumber >= 3) ? sorting.qsortNumber-- : sorting.qsortNumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                </button>
                                                <input
                                                    id="numberOfQsortBase"
                                                    v-model="sorting.qsortNumber"
                                                    class="flex items-center w-32 font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                    max="11"
                                                    min="2"
                                                    name="number-of-qsort"
                                                    type="number"
                                                    value="0"></input>
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(sorting.qsortNumber <= 9) ? sorting.qsortNumber++ : sorting.qsortNumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="w-1/2 overflow-hidden sm:my-px sm:px-px">
                                            <label
                                                class="w-full text-sm font-semibold text-gray-700">{{
                                                    trans('Index Modifier')
                                                }}
                                            </label>
                                            <div
                                                class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg shrink">
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(sorting.qsortIndexModifier >= -99) ? sorting.qsortIndexModifier-- : sorting.qsortNumber">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                </button>
                                                <input
                                                    id="qsortIndexModifier"
                                                    v-model="sorting.qsortIndexModifier"
                                                    class="flex items-center w-32 font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                    max="99"
                                                    min="-99"
                                                    name="qsortIndexModifier"
                                                    type="number"
                                                    value="0"></input>
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(sorting.qsortIndexModifier <= 99) ? sorting.qsortIndexModifier++ : sorting.qsortIndexModifier">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block w-full overflow-visible">
                                            <label class="items-center block mt-2">
                                                <input v-model="sorting.qsortshownumbers"
                                                       class="w-5 h-5 text-blue-600 form-checkbox" type="checkbox"><span
                                                class="ml-2 text-gray-700">{{
                                                    trans('During the interview, show numbers below q-sort.')
                                                }}</span>
                                            </label>
                                            <label class="items-center block mt-2">
                                                <input v-model="sorting.qsortaskextremes"
                                                       class="w-5 h-5 text-blue-600 form-checkbox" type="checkbox"><span
                                                class="ml-2 text-gray-700">{{
                                                    trans('Ask why they sorted in the extremes of the graph.')
                                                }}</span>
                                            </label>
                                            <div class="w-full overflow-visible mt-2" v-show="sorting.qsortaskextremes">
                                                <label class="w-full ml-2 font-semibold text-gray-700">
                                                    {{ trans('Question to ask') }}
                                                    <input v-model="sorting.qsortextremequestion" type="text"
                                                           class="block w-full px-4 py-2 mx-2 leading-normal bg-gray-200 border border-gray-300 appearance-none focus:outline-none focus:ring">
                                                </label>
                                            </div>
                                            <button
                                                class="px-4 py-2 m-2 text-white bg-blue-500 rounded-md select-none hover:bg-blue-800"
                                                type="button"
                                                @click="setTokenNamesToSequentialNumbers"
                                            >
                                                {{ trans('Populate token\'s name with sequential numbers') }}
                                            </button>
                                            <button
                                                v-if="tokenhistory !== 0"
                                                class="px-4 py-2 m-2 text-white bg-blue-500 rounded-md select-none hover:bg-blue-800"
                                                type="button"
                                                @click="undoSetTokenNamesToSequentialNumbers"
                                            >
                                                Undo
                                            </button>
                                        </div>
                                    </div>

                                    <div
                                        v-if="selectedSorting == 1"
                                        class="w-full ml-2">


                                        <ul role="list" class="grid grid-cols-2 gap-2 ">
                                            <li class="flex flex-col col-span-1 text-center bg-white divide-y divide-gray-200 rounded-lg shadow"
                                                v-for="(t,index) in sorting.tokens" :key="index">


                                                <div class="bg-blue-600">
                                                    <div class="px-3 py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                                                        <div class="flex flex-wrap items-center justify-end">

                                                            <button @click="deleteToken(index)" type="button"
                                                                    class="flex -mr-1 rounded-md hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
                                                                <svg class="w-6 h-6 text-white"
                                                                     xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                     viewBox="0 0 24 24" stroke="currentColor"
                                                                     aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="flex flex-col flex-1 p-2">
                                                    <img :id="'token'+index"
                                                         class="flex-shrink-0 w-32 h-32 mx-auto mb-2" src="" alt="">
                                                    <div
                                                        class="relative px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500">
                                                        <label for="name"
                                                               class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
                                                                trans('Token name')
                                                            }}
                                                            *</label>
                                                        <input @input="validTokenName(index,t.name)" v-model="t.name"
                                                               type="text" name="name"
                                                               class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm"
                                                               placeholder="">
                                                    </div>
                                                    <div>
                                                        <label for="combobox"
                                                               class="block mt-1 text-sm font-medium text-gray-700">{{
                                                                trans('Preset tokens')
                                                            }}</label>
                                                        <div class="relative">
                                                            <input v-model="t.file" @focusout="activatecombo(index)"
                                                                   @focus="activatecombo(index)" type="text"
                                                                   class="w-full py-2 pl-3 pr-12 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm z-50"
                                                                   role="combobox" aria-controls="options"
                                                                   aria-expanded="false">
                                                            <button type="button"
                                                                    @click="activatecombo(index)"
                                                                    class="absolute inset-y-0 right-0 flex items-center px-2 rounded-r-md focus:outline-none">

                                                                <svg class="w-5 h-5 text-gray-400"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 20 20" fill="currentColor"
                                                                     aria-hidden="true">
                                                                    <path fill-rule="evenodd"
                                                                          d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                                          clip-rule="evenodd"/>
                                                                </svg>
                                                            </button>

                                                            <ul v-show="t.showCombobox"
                                                                class="absolute z-10 w-full py-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                                id="options" role="listbox">

                                                                <li @mousedown="setTokenFromPreset(index,option.dirname)"
                                                                    class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9"
                                                                    id="option-0" role="option" tabindex="-1"
                                                                    v-for="(option,indexj) in preset" :key="indexj">
                                                                    <div class="flex items-center">
                                                                        <img :src="option.dirname"
                                                                             :alt="option.basename"
                                                                             class="flex-shrink-0 w-6 h-6 rounded-full">
                                                                        <span class="ml-3 truncate">{{
                                                                                option.basename
                                                                            }}</span>
                                                                    </div>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="block text-center file mt-4 z-0">
                                                        <label
                                                            class="upload-label px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 cursor-pointer"
                                                            :for="'file-upload'+index"
                                                        >
                                                            {{ trans('Click to upload') }}
                                                        </label>
                                                        <input
                                                            :id="'file-upload'+index"
                                                            type="file"
                                                            class="file-input"
                                                            accept="image/*"
                                                            @change="handleFileChange(index, $event)"
                                                        />
                                                        <br>
                                                    </div>

                                                </div>

                                            </li>

                                        </ul>


                                    </div>
                                    <div v-if="selectedSorting === 2" class="w-2/3 ml-2">
                                        <label class="font-medium text-gray-700">
                                            {{ trans('Label in the center of the sorting') }} * (1-3)
                                        </label>
                                        <input
                                            v-model="sorting.centerLabel"
                                            :class="response.indexOf(errormessages.centerLabel) > -1 ? 'shadow-sm focus:ring-blue-500 focus:border-blue-700 block w-full sm:text-sm rounded-md border-red-500' : 'shadow-sm focus:ring-blue-500 focus:border-blue-700 block w-full sm:text-sm border-gray-300 rounded-md'"
                                            maxlength="3"
                                            type="text"
                                        />

                                        <div class="w-full mt-4">
                                            <div class="relative flex items-start my-2">
                                                <div class="flex items-center h-5">
                                                    <input
                                                        v-model="sorting.casualcolors"
                                                        id="comments"
                                                        aria-describedby="comments-description"
                                                        name="comments"
                                                        type="checkbox"
                                                        class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-700"
                                                    />
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="comments" class="font-medium text-gray-700">
                                                        {{ trans('Use casual colors') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <label class="mb-2">{{ trans('Name of the sections') }} *</label>

                                            <div v-for="(section, index) in sorting.sections" :key="index"
                                                 class="relative flex items-center space-x-4 my-2">
                                                <input
                                                    v-model="sorting.sections[index].name"
                                                    :class="response.indexOf(errormessages.centerLabel) > -1 ? 'w-1/3 overflow-hidden shadow-sm focus:ring-blue-500 focus:border-blue-700 block sm:text-sm rounded-md border-red-500' : 'w-1/3 overflow-hidden shadow-sm focus:ring-blue-500 focus:border-blue-700 block sm:text-sm border-gray-300 rounded-md'"
                                                    maxlength="20"
                                                    type="text"
                                                />

                                                <div class="w-1/3 relative">
                                                    <button
                                                        type="button"
                                                        :class="[
            'w-full py-2 pl-3 pr-10 text-left border rounded-md shadow-sm focus:outline-none sm:text-sm',
            sorting.casualcolors ? 'bg-gray-200 border-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500'
        ]"
                                                        @click="toggleColorDropdown(index)"
                                                        :disabled="sorting.casualcolors"
                                                    >
        <span
            class="block truncate"
            :style="{ backgroundColor: sorting.sections[index].color }"
        >
            {{ sorting.sections[index].color || trans('Select a color') }}
        </span>
                                                    </button>

                                                    <div
                                                        v-if="!sorting.casualcolors && colorDropdownIndex === index"
                                                        class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
                                                    >
                                                        <div
                                                            class="py-1 overflow-auto text-base rounded-md max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                        >
                                                            <div
                                                                v-for="(color, colorIndex) in colors"
                                                                :key="colorIndex"
                                                                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                                @click="updateSectionColor(index, color)"
                                                            >
                                                        <span
                                                            :style="{ backgroundColor: color }"
                                                            class="inline-block w-6 h-6 mr-2 border border-gray-300 rounded-full"
                                                        ></span>
                                                                {{ color }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div
                                                    v-if="sorting.sections[index].color.length > 0"
                                                    :style="'background-color: ' + sorting.sections[index].color"
                                                    class="w-1/4 h-8 rounded"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div v-if="selectedSorting === 3" class="w-full mt-4">
                                    <div class="block w-full mb-4">
                                        <label class="inline-block text-sm font-semibold text-gray-700">
                                            {{ trans('Labels below the q-sort') }}
                                        </label>
                                        <label
                                            class="inline-block px-2 ml-2 text-sm font-semibold text-white bg-blue-500">
                                            {{ qsortElements }} {{ trans('total elements.') }}
                                        </label>
                                    </div>

                                    <div id="qsortMain" class="block w-full overflow-x-auto mb-6">
                                        <div v-for="(value, index) in sorting.qsort" :key="index"
                                             class="flex flex-row items-center h-10 m-2">
                                            <div class="flex items-center">
                                                <input
                                                    v-model="sorting.qsort[index][0]"
                                                    class="p-2 bg-gray-200 border border-gray-300 focus:border-blue-400 focus:bg-gray-200 rounded-l"
                                                    type="text"
                                                />
                                                <p v-if="sorting.qsortshownumbers"
                                                   class="w-12 mx-2 text-center font-bold qsortbasenumbers">
                                                    {{
                                                        Math.round(index - sorting.qsort.length / 2) + sorting.qsortIndexModifier
                                                    }}
                                                </p>
                                                <button
                                                    class="px-4 py-2 text-gray-600 bg-gray-300 rounded-l focus:outline-none hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="value.length >= 3 ? value.pop() : false"
                                                >
                                                    <span class="text-xl">−</span>
                                                </button>
                                                <button
                                                    class="px-4 py-2 text-gray-600 bg-gray-300 rounded-r focus:outline-none hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="value.length <= 10 ? value.push('--empty--') : false"
                                                >
                                                    <span class="text-xl">+</span>
                                                </button>
                                            </div>
                                            <div class="flex flex-row ml-4">
                                                <div
                                                    v-for="(block, cindex) in value"
                                                    :key="cindex"
                                                    v-if="cindex > 1"
                                                    class="bg-gray-900 w-16 h-8"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="flex flex-wrap w-full mt-4">
                                        <div
                                            v-for="(t, index) in sorting.tokens"
                                            :key="index"
                                            class="relative flex items-center justify-center w-full p-4 sm:w-1/2 md:w-1/3 lg:w-1/4"
                                        >
                                            <div class="w-full p-4 bg-white rounded shadow hover:shadow-md">
                                                <div class="bg-blue-600 p-2 rounded-t">
                                                    <div class="flex justify-between items-center text-white">
                                                        <button
                                                            @click="deleteToken(index)"
                                                            type="button"
                                                            class="text-white focus:outline-none hover:text-gray-200"
                                                        >
                                                            <svg
                                                                class="w-6 h-6"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                aria-hidden="true"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"
                                                                />
                                                            </svg>
                                                        </button>
                                                        {{ trans('Enter token details') }}
                                                    </div>
                                                </div>
                                                <div class="w-full mx-auto mt-4 mb-4">
                                                    <img
                                                        :id="'token' + index"
                                                        :src="t.base64 || ''"
                                                        :alt="trans('Upload a token image')"
                                                        class="w-full h-auto"
                                                    />
                                                </div>
                                                <div class="flex flex-col text-gray-700">
                                                    <label class="mb-2 text-sm font-semibold">
                                                        {{ trans('Token name') }} *
                                                    </label>
                                                    <input
                                                        v-model="t.name"
                                                        class="mb-4 px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg focus:outline-none focus:ring"
                                                        type="text"
                                                        @input="validTokenName(index, t.name)"
                                                    />
                                                    <label class="mb-2 text-sm font-semibold">
                                                        {{ trans('Token description') }}
                                                    </label>
                                                    <textarea
                                                        v-model="t.properties.description"
                                                        class="mb-4 px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg focus:outline-none focus:ring"
                                                    />
                                                </div>
                                                <div>
                                                    <label for="combobox"
                                                           class="block mt-1 text-sm font-medium text-gray-700">{{
                                                            trans('Preset tokens')
                                                        }}</label>
                                                    <div class="relative">
                                                        <input v-model="t.file" @focusout="activatecombo(index)"
                                                               @focus="activatecombo(index)" type="text"
                                                               class="w-full py-2 pl-3 pr-12 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm z-50"
                                                               role="combobox" aria-controls="options"
                                                               aria-expanded="false">
                                                        <button type="button"
                                                                @click="activatecombo(index)"
                                                                class="absolute inset-y-0 right-0 flex items-center px-2 rounded-r-md focus:outline-none"

                                                        >

                                                            <svg class="w-5 h-5 text-gray-400"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 viewBox="0 0 20 20" fill="currentColor"
                                                                 aria-hidden="true">

                                                                <path fill-rule="evenodd"
                                                                      d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                                      clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>

                                                        <ul v-show="t.showCombobox"
                                                            class="absolute z-20 w-full py-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                            id="options" role="listbox">

                                                            <li @mousedown="setTokenFromPreset(index,option.dirname)"
                                                                class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9"
                                                                id="option-0" role="option" tabindex="-1"
                                                                v-for="(option,indexj) in preset" :key="indexj">
                                                                <div class="flex items-center">
                                                                    <img :src="option.dirname"
                                                                         :alt="option.basename"
                                                                         class="flex-shrink-0 w-6 h-6 rounded-full">
                                                                    <span class="ml-3 truncate">{{
                                                                            option.basename
                                                                        }}</span>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="block text-center file mt-4 z-50">
                                                    <label
                                                        class="upload-label px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 cursor-pointer"
                                                        :for="'file-upload' + index"
                                                    >
                                                        {{ trans('Click to upload') }}
                                                    </label>
                                                    <input
                                                        :id="'file-upload'+index"
                                                        type="file"
                                                        class="file-input"
                                                        accept="image/*"
                                                        @change="(event) => handleFileChange(index, event)"
                                                    />
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8"
                                     v-show="selectedSorting === 1">
                                    <label
                                        class="w-full text-sm font-semibold text-gray-700">
                                        {{
                                            trans('Add attributes to tokens? Choose from a set:')
                                        }}
                                    </label>
                                    <CustomDropdown
                                        v-model="sorting.classifier"
                                        :options="classifiers[1]"
                                    />
                                    <img
                                        v-for="(image,index) in classifiers[0]"
                                        :key="index"
                                        v-show="(sorting.classifier ? sorting.classifier.name == image.name : false)"
                                        :src="image.dirname"
                                        class="inline w-5 h-5">
                                </div>
                            </div>
                            <div
                                :class="{'hidden': openTab !== 3, 'block': openTab === 3}">

                                <h6 class="mt-2 subtitle has-text-danger">
                                    {{
                                        trans('Here you can define questions that will be displayed before or after the sorting; you can choose between different kinds of question formats as well.')
                                    }}
                                </h6>
                                <div
                                    class="">
                                    <div
                                        class="">

                                        <div
                                            class="block w-2/3 mb-2 custom-number-input">
                                            <label
                                                class="w-full text-sm font-semibold text-gray-700">{{
                                                    trans('Pre-Sort Questions')
                                                }}
                                            </label>
                                            <div
                                                class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(presort.number >= 1) ? presort.number-- : presort.number">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                </button>
                                                <input
                                                    v-model="presort.number"
                                                    class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                    max="8"
                                                    min="0"
                                                    name="number-of-sections"
                                                    type="number"
                                                    value="0"></input>
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(presort.number <= 19) ? presort.number++ : presort.number">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            v-for="(q,index) in presort.questions"
                                            :key="index"
                                            class="field">
                                            <div
                                                class="relative px-3 py-2 my-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
                                                <label for="name"
                                                       class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
                                                        trans('Question')
                                                    }}</label>
                                                <input v-model="q.question" type="text" name="name" id="name"
                                                       class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm"
                                                       placeholder="">
                                            </div>
                                            <div
                                                class="">
                                                <div
                                                    class="">
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.ismultiple"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @input="unsetifmultiple(index,'presort')">
                                                        {{
                                                            trans('Multiple Choice')
                                                        }}
                                                    </label>
                                                    <br class="flow-root">
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isonechoice"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @input="unsetifonechoice(index,'presort')">
                                                        {{
                                                            trans('One Choice')
                                                        }}
                                                    </label>
                                                </div>
                                                <div
                                                    class="">
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isopen"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox">
                                                        {{
                                                            trans('Open answer')
                                                        }}
                                                    </label>
                                                    <br>
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @input="unsetifscale(index,'presort')">
                                                        {{
                                                            trans('Scale values')
                                                        }}
                                                    </label>
                                                </div>
                                            </div>
                                            <span
                                                v-if="(q.ismultiple || q.isonechoice) && !q.isscale">


                                                                    <div
                                                                        class="block w-2/3 mb-2 custom-number-input">
                                                <label
                                                    class="w-full text-sm font-semibold text-gray-700">{{
                                                        trans('Number of Answers')
                                                    }}
                                                </label>
                                                <div
                                                    class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(q.numberofanswer >= 2) ? q.numberofanswer-- : q.numberofanswer">
                                                        <span
                                                            class="m-auto text-2xl font-thin">−</span>
                                                    </button>
                                                    <input
                                                        v-model="q.numberofanswer"
                                                        class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                        max="8"
                                                        min="0"
                                                        name="number-of-sections"
                                                        type="number"
                                                        value="0"
                                                        @input="normalizeanwers(index,'presort')"></input>
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(q.numberofanswer <= 19) ? q.numberofanswer++ : q.numberofanswer">
                                                        <span
                                                            class="m-auto text-2xl font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>

                    <div
                        v-for="(na,index) in q.numberofanswer" :key="index"
                        class="">
                            <div class="-space-y-px rounded-md shadow-sm isolate">
  <div
      class="relative px-3 py-2 border border-gray-300 rounded-none rounded-md focus-within:z-10 focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
    <label for="answers" class="block text-xs font-medium text-gray-900">{{ trans('Answer') }}</label>
    <input v-model="q.answers[na-1]" type="text" name="answers" id="answers"
           class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
  </div>
</div>

                    </div>
                </span>
                                            <hr>
                                            <span
                                                v-if="(!q.ismultiple || !q.isonechoice) && q.isscale">
                                        <div
                                            class="">
                        <div
                            class="">



                                        <div
                                            class="relative px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Minimum Value')
      }}</label>
  <input v-model="q.scalemin" type="number"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                                        <div
                                            class="relative px-3 py-2 mt-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Label Minimum Value')
      }}</label>
  <input v-model="q.minlabel" type="text"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>


                        </div>
                        <div
                            class="">
                                                                        <div
                                                                            class="relative px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Max Value')
      }}</label>
  <input v-model="q.scalemax" type="number"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                                        <div
                                            class="relative px-3 py-2 mt-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Label Max Value')
      }}</label>
  <input v-model="q.maxlabel" type="text"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                        </div>
                    </div>
                    <hr>
                </span>
                                        </div>
                                    </div>
                                    <div
                                        class="">

                                        <div
                                            class="block w-2/3 mb-2 custom-number-input">
                                            <label
                                                class="w-full text-sm font-semibold text-gray-700">{{
                                                    trans('Post-Sort Questions')
                                                }}
                                            </label>
                                            <div
                                                class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(postsort.number >= 1) ? postsort.number-- : postsort.number">
                                                    <span
                                                        class="m-auto text-2xl font-thin">−</span>
                                                </button>
                                                <input
                                                    v-model="postsort.number"
                                                    class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                    max="8"
                                                    min="0"
                                                    name="number-of-sections"
                                                    type="number"
                                                    value="0"></input>
                                                <button
                                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                    @click.prevent="(postsort.number <= 19) ? postsort.number++ : postsort.number">
                                                    <span
                                                        class="m-auto text-2xl font-thin">+</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div
                                            v-for="(q,index) in postsort.questions"
                                            class="field">
                                            <div
                                                class="relative px-3 py-2 my-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
                                                <label for="name"
                                                       class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
                                                        trans('Question')
                                                    }}</label>
                                                <input v-model="q.question" type="text" name="name" id="name"
                                                       class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm"
                                                       placeholder="">
                                            </div>
                                            <div
                                                class="">
                                                <div
                                                    class="">
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.ismultiple"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @input="unsetifmultiple(index,'postsort')">
                                                        {{
                                                            trans('Multiple Choice')
                                                        }}
                                                    </label>
                                                    <br>
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isonechoice"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @input="unsetifonechoice(index,'postsort')">
                                                        {{
                                                            trans('One Choice')
                                                        }}
                                                    </label>
                                                </div>
                                                <div
                                                    class="">
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isopen"
                                                            :disabled="q.isscale"
                                                            checked
                                                            type="checkbox">
                                                        {{
                                                            trans('Open Answer')
                                                        }}
                                                    </label>
                                                    <br>
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.isscale"
                                                            checked
                                                            type="checkbox"
                                                            @click="unsetifscale(index,'postsort')">
                                                        {{
                                                            trans('Scale values')
                                                        }}
                                                    </label>
                                                    <label
                                                        class="checkbox">
                                                        <input
                                                            v-model="q.canShowSorting"
                                                            type="checkbox"
                                                        >
                                                        {{
                                                            trans('Show Sorting')
                                                        }}
                                                    </label>


                                                </div>
                                            </div>
                                            <span
                                                v-if="(q.ismultiple || q.isonechoice) && !q.isscale">
                                                <div
                                                    class="block w-2/3 mb-2 custom-number-input">
                                                <label
                                                    class="w-full text-sm font-semibold text-gray-700">{{
                                                        trans('Number of Answers')
                                                    }}
                                                </label>
                                                <div
                                                    class="relative flex flex-row w-full h-10 mt-1 bg-transparent rounded-lg">
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(q.numberofanswer >= 2) ? q.numberofanswer-- : q.numberofanswer">
                                                        <span
                                                            class="m-auto text-2xl font-thin">−</span>
                                                    </button>
                                                    <input
                                                        v-model="q.numberofanswer"
                                                        class="flex items-center w-full font-semibold text-center text-gray-700 bg-white outline-none focus:outline-none text-md hover:text-black focus:text-black md:text-basecursor-default"
                                                        max="8"
                                                        min="0"
                                                        name="number-of-sections"
                                                        type="number"
                                                        value="0"
                                                        @input="normalizeanwers(index,'postsort')"></input>
                                                    <button
                                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r cursor-pointer hover:text-gray-700 hover:bg-gray-400"
                                                        @click.prevent="(q.numberofanswer <= 19) ? q.numberofanswer++ : q.numberofanswer">
                                                        <span
                                                            class="m-auto text-2xl font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>

                    <div
                        v-for="(na,index) in q.numberofanswer" :key="index"
                        class="">
                            <div class="-space-y-px rounded-md shadow-sm isolate">
                          <div
                              class="relative px-3 py-2 border border-gray-300 rounded-none rounded-md focus-within:z-10 focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
                            <label for="answers" class="block text-xs font-medium text-gray-900">{{ trans('Answer') }}</label>
                            <input v-model="q.answers[na-1]" type="text" name="answers" id="answers"
                                   class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
                          </div>
                        </div>

                    </div>
                </span>
                                            <hr>
                                            <span
                                                v-if="(!q.ismultiple || !q.isonechoice) && q.isscale">
                    <div
                        class="">
                        <div
                            class="">



                                        <div
                                            class="relative px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Minimum Value')
      }}</label>
  <input v-model="q.scalemin" type="number"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                                        <div
                                            class="relative px-3 py-2 mt-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Label Minimum Value')
      }}</label>
  <input v-model="q.minlabel" type="text"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>


                        </div>
                        <div
                            class="">
                                                                        <div
                                                                            class="relative px-3 py-2 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Max Value')
      }}</label>
  <input v-model="q.scalemax" type="number"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                                        <div
                                            class="relative px-3 py-2 mt-4 border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-700">
  <label for="name" class="absolute inline-block px-1 -mt-px text-xs font-medium text-gray-900 bg-white -top-2 left-2">{{
          trans('Label Max Value')
      }}</label>
  <input v-model="q.maxlabel" type="text"
         class="block w-full p-0 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 sm:text-sm" placeholder="">
</div>

                        </div>
                    </div>
                    <hr>
                </span>
                                        </div>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div
            class="flex flex-row justify-around w-full pb-4">
            <input
                class="flex px-4 py-2 font-semibold text-blue-700 bg-transparent border border-blue-500 hover:bg-blue-500 hover:text-white hover:border-transparent"
                type="button"
                value="Cancel"
                @click="resetStudy">

            <input
                v-show="openTab === 20 && selectedSorting == 2"
                :class="{'is-loading' : loading}"
                :value="trans('Check preview in Sorting')"
                class="px-4 py-2 font-bold text-white bg-blue-500 hover:bg-blue-700 focus:outline-none"
                type="button"
                @click.prevent="showSortingPreview"
            >

            <input
                v-if="!isedit && openTab === 3"
                :class="{'is-loading' : loading}"
                :value="trans('Create new project')"
                class="px-4 py-2 font-bold text-white bg-green-500 hover:bg-green-700 focus:outline-none"
                type="submit"
                @click.prevent="savestudy()">
            <input
                v-if="isedit && openTab === 3"
                :class="{'is-loading' : loading}"
                :value="trans('Edit current project')"
                class="px-4 py-2 font-bold text-white bg-green-500 hover:bg-green-700 focus:outline-none"
                type="submit"
                @click.prevent="editstudy()">
            <input
                v-if="openTab !== 3"
                class="px-4 py-2 font-bold text-white bg-green-500 hover:bg-green-700 focus:outline-none"
                :value="trans('Next')"
                type="button"
                @click.prevent="openTab++">

        </div>

    </div>


</template>

<script>
import Snackbar from "./snackbar.vue";
import CustomDropdown from "./CustomDropdown.vue";

export default {
    components: {CustomDropdown, Snackbar},
    props: {
        isedit: String / Boolean, preset: Array, classifiers: Array, studydata: {
            type: Object, require: false, default: null,
        },

    }, data() {
        return {
            snackbarMessage: '',
            showSnackbar: false,
            showSelectSorting: false,
            sortingTypes: ["Circle Sorting", "Network Sorting", "Q-Sort"],
            sortingsDescription: ["For researching media repertoires or repertoires of other kinds of objects.", "For social network analysis and other personal network reconstructions.", "For Q-methodology research."],
            tokenhistory: 0,
            colorDropdownIndex: null,
            colors: [
                '#CC1F1A', '#DE751F', '#F2D024', '#1F9D55', '#38A89D', '#2779BD', '#5661B3', '#794ACF', '#EB5286',
            ],
            sorting_preview: false,
            openTab: 1,
            inputLength: {
                studyname: 80, short_description: 250, author: 250, sorting_description: 250,
            },
            baseUrl: window.location.origin || '',
            loading: false,
            name: '',
            shortDescription: '',
            author: '',
            editable: false,
            fetching: false,
            selectedSorting: 1,
            sorting: {
                id: 1,
                qsortshownumbers: true,
                qsortaskextremes: false,
                qsortextremequestion: "",
                minvalue: 0,
                maxvalue: 1,
                centerLabel: '',
                qsort: [],
                qsortIndexModifier: 0,
                qsortNumber: 5,
                numberofcircles: 3,
                tokennumber: 1,
                description: '',
                sectionNumber: 2,
                casualcolors: false,
                sections: [
                    {
                        name: '', color: '',
                    }, {
                        name: '', color: '',
                    },
                ], tokens: [
                    {
                        name: '', file: null, path: '', fileUpload: null, showCombobox: false, properties: {
                            description: '',
                        },
                    },
                ],

            },
            presort: {
                number: 1,
                questions: [
                    {
                        question: '',
                        isopen: false,
                        ismultiple: true,
                        isonechoice: false,
                        isscale: false,
                        scalemin: 0,
                        scalemax: 100,
                        minlabel: '',
                        maxlabel: '',
                        numberofanswer: 2,
                        answers: [],
                    },

                ],
            }, postsort: {
                number: 1,
                questions: [
                    {
                        question: '',
                        canShowSorting: false,
                        isopen: false,
                        ismultiple: true,
                        isonechoice: false,
                        isscale: false,
                        scalemin: 0,
                        scalemax: 100,
                        numberofanswer: 2,
                        minlabel: '',
                        maxlabel: '',
                        answers: [],
                    },

                ],
            }, response: '', errormessages: {
                studyname: this.trans('Name of the Project required.') + '<br>',
                author: this.trans('Author\'s name required.') + '<br>',
                short_description: this.trans('Project description required required.') + '<br>',
                tokens: this.trans('Please fill all the Tokens\'s names.') + '<br>',
                answertype: this.trans('Please check that all the answer have a type.') + '<br>',
                question: this.trans('Please check all the questions.') + '<br>',
                multipleanswer: this.trans('One of your Multiple Choice is empty.') + '<br>',
                centerLabel: this.trans('Select a label for the center of the sorting.') + '<br>',
                sections: this.trans('Please fill all the sections names.') + '<br>',
                colors: this.trans('Please select all the section\'s label colors or select casual colors.') + '<br>',
                qsortextremequestion: this.trans('Please insert a question for the extremes')
            },
        };
    }, computed: {
        'qsortElements': function () {

            let totalLength = 0;
            this.sorting.qsort.forEach(function (element) {
                totalLength = totalLength + element.length - 2;
            });

            let self = this;

            setTimeout(() => {
                let direction = (totalLength - self.sorting.tokennumber);
                if (direction > 0) {
                    for (let i = 0;
                         i < direction;
                         i++) {
                        self.sorting.tokennumber++;
                    }
                } else if (totalLength == 0) {
                    // special case
                    self.sorting.tokens = [];
                } else {
                    // decrease
                    for (let i = 0;
                         i < Math.abs(direction);
                         i++) {
                        self.sorting.tokennumber--;

                    }
                }
            }, 50);


            return totalLength;


        }, 'sortingname': function () {
            if (this.sorting.id === 1) {
                this.trans('Circle Sorting');
            }
            if (this.sorting.id === 2) {
                this.trans('Network Sorting');
            }
            if (this.sorting.id === 3) {
                this.trans('Q-Sort');
            }
        },
    }, created() {

        if (window.location.search.slice(1).trim() !== '') {
            let urlSearch = new URLSearchParams(window.location.search.slice(1));
            let tab = urlSearch.get('tab');
            this.openTab = parseInt(tab);
        }

        setTimeout(function () {
            document.getElementById('sortingDescription').addEventListener('keydown', (e) => {
                if ((e.keyCode == 220)) {
                    e.preventDefault();
                }
            });
            document.getElementById('studyname').addEventListener('keydown', (e) => {
                if ((e.keyCode == 13)) {
                    e.preventDefault();
                }
            });
        }, 50);

        if (this.isedit) {
            this.fetchAndFormatDataForedit();
        } else {
            for (let i = 0;
                 i < this.sorting.qsortNumber;
                 i++) {
                this.sorting.qsort.push(['', '']);
            }
            this.fetching = false;
            this.$forceUpdate();

        }

    }, watch: {
        'selectedSorting': function (newVal, oldVal) {
            this.sorting.id = newVal;
            while (this.sorting.tokennumber > 0) {
                this.sorting.tokennumber--;
            }

        }, 'sorting.sectionNumber': function (newVal, oldVal) {
            if (!this.fetching) {
                if (newVal < 0 || oldVal < 0) {
                    newVal = 0;
                    oldVal = 0;
                }
                let direction = (newVal - oldVal);

                if (direction > 0) {
                    for (let i = 0;
                         i < direction;
                         i++) {
                        this.sorting.sections.push({
                            name: '', color: '',
                        });
                    }
                } else {
                    // decrease
                    for (let i = 0;
                         i < Math.abs(direction);
                         i++) {
                        this.sorting.sections.pop();

                    }
                }
            }
        }, 'presort.number': function (newVal, oldVal) { // watch it
            if (newVal < 0 || oldVal < 0) {
                newVal = 0;
                oldVal = 0;
            }
            let direction = (newVal - oldVal);

            if (direction > 0) {
                for (let i = 0;
                     i < direction;
                     i++) {
                    let questiontemplate = {
                        question: '',
                        isopen: true,
                        ismultiple: true,
                        isonechoice: false,
                        isscale: false,
                        scalemin: 0,
                        scalemax: 100,
                        minlabel: '',
                        maxlabel: '',
                        numberofanswer: 1,
                        answers: [],
                    };
                    this.presort.questions.push(Object.assign({}, questiontemplate));
                }
            } else if (newVal == 0) {
                // special case
                this.presort.questions = [];
            } else {
                // decrease
                for (let i = 0;
                     i < Math.abs(direction);
                     i++) {
                    this.presort.questions.pop();

                }
            }

        }, 'presort.questions': function (newVal, oldVal) {

        }, 'postsort.number': function (newVal, oldVal) {

            if (newVal < 0 || oldVal < 0) {
                newVal = 0;
                oldVal = 0;
            }

            let direction = (newVal - oldVal);

            if (direction > 0) {
                for (let i = 0;
                     i < direction;
                     i++) {
                    let questiontemplate = {
                        question: '',
                        isopen: true,
                        ismultiple: true,
                        isonechoice: false,
                        isscale: false,
                        canShowSorting: false,
                        scalemin: 0,
                        scalemax: 100,
                        minlabel: '',
                        maxlabel: '',
                        numberofanswer: 1,
                        answers: [],
                    };
                    this.postsort.questions.push(Object.assign({}, questiontemplate));
                }
            } else if (newVal == 0) {
                // special case
                this.postsort.questions = [];
            } else {
                // decrease
                for (let i = 0;
                     i < Math.abs(direction);
                     i++) {
                    this.postsort.questions.pop();
                }
            }

        }, 'sorting.qsortNumber': function (newVal, oldVal) {
            if (!this.fetching) {
                if (newVal < 0 || oldVal < 0) {
                    newVal = 0;
                    oldVal = 0;
                }

                let direction = (newVal - oldVal);

                if (direction > 0) {
                    for (let i = 0;
                         i < direction;
                         i++) {
                        this.sorting.qsort.push(['', '']);
                    }
                } else if (newVal == 0) {
                    // special case
                    this.sorting.qsort = [];
                } else {
                    // decrease
                    for (let i = 0;
                         i < Math.abs(direction);
                         i++) {
                        this.sorting.qsort.pop();
                    }
                }
            }
        }, 'sorting.tokennumber': function (newVal, oldVal) {

            if (!this.fetching) {

                if (newVal < 0 || oldVal < 0) {
                    newVal = 0;
                    oldVal = 0;
                }

                let direction = (newVal - oldVal);

                if (direction > 0) {

                    for (let i = 0;
                         i < direction;
                         i++) {
                        let tokentemplate = {
                            name: '', file: null, path: '', properties: {description: ''},
                        };
                        this.sorting.tokens.push(Object.assign({}, tokentemplate));
                    }

                } else if (newVal == 0) {
                    // special case
                    this.sorting.tokens = [];
                } else {
                    // decrease
                    for (let i = 0;
                         i < Math.abs(direction);
                         i++) {
                        this.sorting.tokens.pop();

                    }
                }
            }
        }, 'sorting.tokens': function (newVal, oldVal) {

        },
    }, methods: {
        showSnackbarMessage(message) {
            this.snackbarMessage = message;
            this.showSnackbar = true;
            setTimeout(() => {
                this.showSnackbar = false;
            }, 3000);  // Snackbar duration
        },
        hideAndAssignSorting(sortingId) {
            this.selectedSorting = sortingId + 1;
            this.showSelectSorting = false;
        },
        activatecombo: function (index) {
            this.sorting.tokens[index].showCombobox = !this.sorting.tokens[index].showCombobox;
            this.$forceUpdate()

        },
        deleteToken: function (index) {
            let self = this;

            let isCircleSorting = this.selectedSorting === 1
            let isQsort = this.selectedSorting === 3
            if (isCircleSorting) {
                this.$delete(this.sorting.tokens, index)
                this.fetching = true;
                document.getElementById('token' + index).src = "";
                for (let i = index; i < this.sorting.tokennumber - 1; i++) {
                    document.getElementById('token' + i).src = document.getElementById('token' + (i + 1)).src;
                }
                this.sorting.tokennumber--;

                setTimeout(() => {

                    self.fetching = false
                }, 100)
            } else if (isQsort && this.sorting.tokens.length > 1) {

                let arrayOfLengths = []
                this.$delete(this.sorting.tokens, index)
                this.fetching = true;
                document.getElementById('token' + index).src = "";
                for (let i = index; i < this.sorting.tokennumber - 1; i++) {
                    document.getElementById('token' + i).src = document.getElementById('token' + (i + 1)).src;
                }
                // delete a token from the latest column but need to shift all of them
                this.sorting.qsort.forEach((a) => {
                    arrayOfLengths.push(a.length);
                });

                // Find the index of the longest array in `arrayOfLengths` that has more than 2 elements.
                // If no such array exists, find the index of the longest array overall.
                let bestIndex = 0;
                let max = -Infinity;
                for (var i = 0; i < arrayOfLengths.length; i++) {
                    if ((arrayOfLengths[i].length > 2) || (max < arrayOfLengths[i] && i >= bestIndex)) {
                        bestIndex = i;
                        max = arrayOfLengths[i];
                    }
                }
                setTimeout(() => {

                    self.fetching = false
                }, 100)
                this.sorting.qsort[bestIndex].pop();
                this.$forceUpdate()


            }
        },
        setTokenNamesToSequentialNumbers: function () {
            this.tokenhistory = this.copyObject(this.sorting.tokens);
            this.sorting.tokens.forEach((t, key) => {
                t.name = key + 1;
            });
        }, undoSetTokenNamesToSequentialNumbers: function () {

            if (this.tokenhistory.length > 0) {
                this.sorting.tokens = this.copyObject(this.tokenhistory);
                this.tokenhistory = 0;
            }

        }, showSortingPreview: function () {
            this.$refs["sorting-preview"].toggleSortingPreviewModal();
        }, toggleTabs: function (tabNumber) {
            this.openTab = tabNumber;
        },
        fetchAndFormatDataForedit: function () {

            function substrInBetween(whole_str, str1, str2) {
                return whole_str.substring(whole_str.indexOf(str1) + str1.length, whole_str.lastIndexOf(str2));
            }

            this.fetching = true;
            // study info
            this.name = this.studydata.name;
            this.shortDescription = this.studydata.description;
            this.author = this.studydata.author;

            // questions
            this.presort.number = this.studydata.q.presort.count;
            this.postsort.number = this.studydata.q.postsort.count;
            if (this.studydata.q.extremeQuestion.hasOwnProperty('qsortextremequestion')) {
                this.sorting.qsortaskextremes = true;
                this.sorting.qsortextremequestion = this.studydata.q.extremeQuestion.qsortextremequestion;
            }
            // sorting
            let detailsArray = this.studydata.sortings[0].pivot.details.split('||');

            this.sorting.numberofcircles = parseInt(detailsArray[0].substr(detailsArray[0].indexOf('|') + 1));
            if (detailsArray[1]) {
                this.sorting.description = detailsArray[1].substr(detailsArray[1].indexOf('|') + 1);
            } else {
                this.sorting.description = '';
            }

            this.sorting.classifier = this.studydata.sortings[0].pivot.details.includes('classifier|') ? {
                name: detailsArray[2].substr(detailsArray[2].indexOf('|') + 1),
            } : '';

            this.sorting.id = parseInt(this.studydata.sorting[0].id);
            this.selectedSorting = parseInt(this.studydata.sorting[0].id);
            if (this.studydata.sorting[0].id === 2) {
                this.sorting.sections = [];
                this.sorting.sectionNumber = parseInt(this.studydata.sortings[0].pivot.details.substr(this.studydata.sortings[0].pivot.details.indexOf('divisions|') + 10, 1));

                let self = this;
                let namesAndColor = this.studydata.sortings[0].pivot.details.substr(this.studydata.sortings[0].pivot.details.indexOf('names|') + 6).split('||')[0].split('|');

                namesAndColor.forEach(function (value, key) {
                    let color = value.substr(value.indexOf(';color:') + 7, value.length) === 'casual' ? '' : value.substr(value.indexOf(';color:') + 7, value.length);
                    self.sorting.sections.push({
                        name: value.substr(0, value.indexOf(';color:')),
                        color: color,
                    });
                });

                if (this.sorting.sections[0].color === '') {
                    this.sorting.casualcolors = true;
                }

                this.sorting.centerLabel = substrInBetween(this.studydata.sortings[0].pivot.details, 'center|', '||');
            }


            let self = this;
            this.sorting.tokennumber = this.studydata.tokens.length;


            if (self.studydata.sorting[0].id === 3) {
                this.sorting.qsortNumber = 0;
                let qsort = this.studydata.sortings[0].pivot.details.substr(this.studydata.sortings[0].pivot.details.indexOf('qsort|') + 6).split('|separator|');
                if (qsort[qsort.length - 1].includes("yes")) {
                    this.sorting.qsortshownumbers = true;
                } else {
                    this.sorting.qsortshownumbers = false;
                }
                qsort.pop();

                let qsortArray = [];
                Object.keys(qsort).forEach(function (key) {
                    let value = qsort[key];
                    let av = value.replaceAll('--empty--', '').split('|');
                    qsortArray.push(av);
                });


                this.sorting.qsortIndexModifier = Math.abs(Math.round(0 - qsortArray.length / 2)) + parseInt(qsortArray[0][1]);
                this.sorting.qsortNumber = qsortArray.length;
                this.sorting.qsort = qsortArray;

                for (let i = 0; i < self.qsortElements; i++) {
                    self.sorting.tokens[i] = self.studydata.tokens[i];
                    self.sorting.tokens[i].properties = JSON.parse(self.sorting.tokens[i].properties);


                    if (self.sorting.tokens[i].hasOwnProperty('image_path') && self.sorting.tokens[i].image_path.indexOf('presets') === -1) {
                        self.sorting.tokens[i].base64 = self.sorting.tokens[i].image_path;
                    }
                }

            } else {
                for (let i = 0; i < this.studydata.tokens.length; i++) {
                    this.sorting.tokens[i] = this.studydata.tokens[i];


                    if (this.sorting.tokens[i].hasOwnProperty('image_path') && this.sorting.tokens[i].image_path.indexOf('presets') == -1) {
                        this.sorting.tokens[i].base64 = this.sorting.tokens[i].image_path;
                    }
                }
            }


            setTimeout(function () {

                if (self.studydata.sorting[0].id === 1 && self.studydata.tokens.length === 0) {
                    self.sorting.tokens = []
                }
                for (let i = 0;
                     i < self.studydata.tokens.length;
                     i++) {

                    document.querySelector('#token' + i).src = self.studydata.tokens[i].image_path;
                }
                // Presort
                self.processQuestions(self.presort, self.studydata.q.presort, 'presort');

                // Postsort
                self.processQuestions(self.postsort, self.studydata.q.postsort, 'postsort');


                self.fetching = false;
            }, 100);
        }, processQuestions: function (questions, studyQuestions, type) {
            for (let i = 0; i < questions.number; i++) {
                questions.questions[i].question = studyQuestions[i].question;
                questions.questions[i].id = studyQuestions[i].id;

                questions.questions[i].isopen = false;
                questions.questions[i].ismultiple = false;
                questions.questions[i].isscale = false;
                questions.questions[i].isonechoice = false;

                if (type === 'postsort') {
                    questions.questions[i].canShowSorting = studyQuestions[i].canShowSorting;
                }

                studyQuestions[i].answers.forEach((answer, k) => {
                    if (answer.answer.type === 'open') {
                        questions.questions[i].isopen = true;
                    }

                    if (answer.answer.type === 'multi') {
                        questions.questions[i].numberofanswer = studyQuestions[i].answers.filter(a => a.answer.type === 'multi').length;
                        questions.questions[i].ismultiple = true;

                        studyQuestions[i].answers.forEach((ans, x) => {
                            if (ans.answer.type === 'multi') {
                                questions.questions[i].answers[x] = ans.answer.answer;
                            }
                        });
                    }

                    if (answer.answer.type === 'onechoice') {
                        questions.questions[i].numberofanswer = studyQuestions[i].answers.filter(a => a.answer.type === 'onechoice').length;
                        questions.questions[i].isonechoice = true;

                        studyQuestions[i].answers.forEach((ans, x) => {
                            if (ans.answer.type === 'onechoice') {
                                questions.questions[i].answers[x] = ans.answer.answer;
                            }
                        });
                    }

                    if (answer.answer.type === 'scale') {
                        questions.questions[i].isopen = false;
                        questions.questions[i].ismultiple = false;
                        questions.questions[i].isonechoice = false;
                        questions.questions[i].isscale = true;
                        questions.questions[i].scalemin = answer.answer.answer.min;
                        questions.questions[i].minlabel = answer.answer.answer.minlabel;
                        questions.questions[i].scalemax = answer.answer.answer.max;
                        questions.questions[i].maxlabel = answer.answer.answer.maxlabel;
                    }
                });
            }
        },
        reverseArray: function () {
            if (this.selectedSorting === 3) {
                const qsortbasenumbers = document.getElementsByClassName("qsortbasenumbers");

                this.sorting.qsort.forEach((q, key) => {
                    // Check if there are elements with the class 'qsortbasenumbers'
                    if (qsortbasenumbers.length > 0) {
                        // Update the second value in the array with the corresponding text
                        q[1] = qsortbasenumbers[key].innerText;
                    }
                    // Reverse the array
                    q.reverse();
                });
            }
        }, savestudy: function () {

            if (this.validdata()) {
                let study = {};
                this.loading = true;
                this.reverseArray()
                study = {
                    name: this.name,
                    author: this.author,
                    description: this.shortDescription,
                    sorting: this.sorting,
                    presort: this.presort,
                    postsort: this.postsort
                };


                window.axios.post('../studies', study).then(response => {
                    if (response.message) {
                        this.response = response.message;
                    } else {
                        this.showSnackbarMessage(response.data.message);

                    }

                    setTimeout(function () {
                        window.location.href = '../';
                    }, 1000);
                    this.loading = false;
                }).catch(error => {

                    if (error.message) {
                        this.showSnackbarMessage(error.message);
                    } else {
                        this.showSnackbarMessage(error.response.data);
                    }

                    this.loading = false;
                });
            }

        }, editstudy: function () {

            if (this.validdata()) {
                let study = {};
                this.loading = true;

                if (this.sorting.id === 2) {
                    this.sorting.tokens = [];

                }

                this.reverseArray()

                study = {
                    ...study,
                    id: this.studydata.id,
                    name: this.name,
                    author: this.author,
                    description: this.shortDescription,
                    sorting: this.sorting,
                    presort: this.presort,
                    postsort: this.postsort,
                };


                window.axios.patch(window.location.origin + this.productionUrl + '/studies/' + study['id'], study).then(response => {

                    if (response.message) {
                        this.response = response.message;
                    } else {
                        this.showSnackbarMessage(response.data.message);
                    }
                    setTimeout(function () {
                        window.location.href = '../../';
                    }, 1000);
                    this.loading = false;
                }).catch(error => {

                    if (error.message) {
                        this.showSnackbarMessage(error.message);
                        this.$buefy.snackbar.open();
                    } else {
                        this.showSnackbar(error.response.data);
                    }

                    this.loading = false;
                });
            }
        }, validdata: function () {
            this.response = '';

            if (this.name == '') {
                this.response += this.errormessages.studyname;
            }
            if (this.author == '') {
                this.response += this.errormessages.author;
            }
            if (this.shortDescription == '') {
                this.response += this.errormessages.short_description;
            }

            if (this.sorting.id !== 2 && this.sorting.tokens.find((token) => token.name === '' || token.name === undefined)) {
                this.response += this.errormessages.tokens;
            }


            if (this.sorting.id == 2 && this.sorting.centerLabel == '') {
                this.response += this.errormessages.centerLabel;
            }

            if (this.sorting.id == 2 && this.sorting.sections.some(section => section.name === '')) {
                this.response += this.errormessages.sections;
            }

            if (this.sorting.id == 2 && this.sorting.casualcolors === false && this.sorting.sections.find(section => section.color === '')) {
                this.response += this.errormessages.colors;
            }

// Check if answertype error exists in postsort.questions
            if (this.postsort.questions.some(o => !o.isopen && !o.ismultiple && !o.isscale && !o.isonechoice)) {
                this.response += this.errormessages.answertype;
            }

// Check if answertype error exists in presort.questions
            if (this.presort.questions.some(o => !o.isopen && !o.ismultiple && !o.isscale && !o.isonechoice)) {
                this.response += this.errormessages.answertype;
            }

// Check if any presort question is empty
            if (this.presort.questions.some(o => o.question === '')) {
                this.response += this.errormessages.question;
            }

// Check if any postsort question is empty
            if (this.postsort.questions.some(o => o.question === '')) {
                this.response += this.errormessages.question;
            }

// Check if presort multiple answers are correct
            if (this.presort.questions.some(o => o.ismultiple && (o.numberofanswer != (o.answers.length - o.answers.filter(u => u === '').length)))) {
                this.response += this.errormessages.multipleanswer;
            }

// Check if postsort multiple answers are correct
            if (this.postsort.questions.some(o => o.ismultiple && (o.numberofanswer != o.answers.length))) {
                this.response += this.errormessages.multipleanswer;
            }


            if (this.sorting.qsortaskextremes && this.sorting.qsortextremequestion === '') {
                this.response += this.errormessages.qsortextremequestion;
            }

            if (this.response == '') {
                return true;
            } else {
                return false;
            }

        }, drawImageProp: function (ctx, img, x, y, w, h, offsetX, offsetY) {
            /**
             * By Ken Fyrstenberg Nilsen
             *
             * drawImageProp(context, image [, x, y, width, height [,offsetX, offsetY]])
             *
             * If image and context are only arguments rectangle will equal canvas
             * https://stackoverflow.com/questions/21961839/simulation-background-size-cover-in-canvas/21961894#21961894
             */

            if (arguments.length === 2) {
                x = y = 0;
                w = ctx.canvas.width;
                h = ctx.canvas.height;
            }

            // default offset is center
            offsetX = typeof offsetX === 'number' ? offsetX : 0.5;
            offsetY = typeof offsetY === 'number' ? offsetY : 0.5;

            // keep bounds [0.0, 1.0]
            if (offsetX < 0) {
                offsetX = 0;
            }
            if (offsetY < 0) {
                offsetY = 0;
            }
            if (offsetX > 1) {
                offsetX = 1;
            }
            if (offsetY > 1) {
                offsetY = 1;
            }

            let iw = img.width, ih = img.height, r = Math.min(w / iw, h / ih), nw = iw * r,   // new prop. width
                nh = ih * r,   // new prop. height
                cx, cy, cw, ch, ar = 1;

            // decide which gap to fill
            if (nw < w) {
                ar = w / nw;
            }
            if (Math.abs(ar - 1) < 1e-14 && nh < h) {
                ar = h / nh;
            }  // updated
            nw *= ar;
            nh *= ar;

            // calc source rectangle
            cw = iw / (nw / w);
            ch = ih / (nh / h);

            cx = (iw - cw) * offsetX;
            cy = (ih - ch) * offsetY;

            // make sure source rectangle is valid
            if (cx < 0) {
                cx = 0;
            }
            if (cy < 0) {
                cy = 0;
            }
            if (cw > iw) {
                cw = iw;
            }
            if (ch > ih) {
                ch = ih;
            }

            // fill image in dest. rectangle
            ctx.drawImage(img, cx, cy, cw, ch, x, y, w, h);
        }, validTokenName: function (index, name) {

            name = name.split('/').join('');

            this.sorting.tokens[index].name = name;

        },
        handleFileChange(index, event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (fileEvent) => {
                const im = new Image();
                im.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = 100;
                    canvas.height = 100;
                    if (im.width < 100 || im.height < 100) {
                        ctx.drawImage(im, 0, 0, 100, 100, 0, 0, canvas.width, canvas.height);
                    } else {
                        this.drawImageProp(ctx, im, 0, 0, canvas.width, canvas.height);
                    }
                    const newimgUri = canvas.toDataURL('image/*').toString();

                    // Set the processed image as the src for the corresponding img element
                    const imgElement = document.getElementById('token' + index);
                    if (imgElement) {
                        imgElement.src = newimgUri;
                    }

                    // Also, update the token data
                    this.sorting.tokens[index].base64 = newimgUri;
                    if (!this.sorting.tokens[index].name) {
                        this.sorting.tokens[index].name = file.name;
                    }
                };
                im.src = fileEvent.target.result;
            };
            reader.readAsDataURL(file);
        },

        checkSnackbarMessage() {
            const message = localStorage.getItem("snackbarMessage");
            if (message) {
                this.showSnackbarMessage(message);
                localStorage.removeItem("snackbarMessage");
            }
        },
        resetStudy: function () {
            this.name = '';
            this.shortDescription = '';
            this.author = '';
            this.sorting.id = 1;
            this.sorting.tokennumber = 1;
            this.sorting.numberofcircles = 3;
            this.response = '';
            let self = this;
            setTimeout(function () {

                self.presort.number = 1;
                self.presort.questions[0].answers = 0;
                self.presort.questions[0].answers = 2;

                self.postsort.number = 1;
                self.postsort.questions[0].answers = 0;
                self.postsort.questions[0].answers = 2;

                self.$forceUpdate();

            }, 100);

        }, unsetifscale: function (index, detail) {
            this[detail].questions[index].ismultiple = false;
            this[detail].questions[index].isopen = false;
            this[detail].questions[index].isonechoice = false;
            this[detail].questions[index].answers = [];
            this[detail].questions[index].numberofanswer = 0;
        }, unsetifonechoice: function (index, detail) {
            if (this[detail].questions[index].ismultiple) {
                this[detail].questions[index].ismultiple = false;
                this[detail].questions[index].scale = false;
            }
        }, unsetifmultiple: function (index, detail) {
            if (this[detail].questions[index].isonechoice == true) {
                this[detail].questions[index].isonechoice = false;
                this[detail].questions[index].scale = false;
            }
        }, normalizeanwers: function (index, detail) {

            let count = this[detail].questions[index].answers.length - parseInt(this[detail].questions[index].numberofanswer, 10);
            if (count > 0) {
                for (let i = 0;
                     i < count;
                     i++) {
                    this[detail].questions[index].answers.pop();
                }
            }
        },
        toggleColorDropdown(index) {
            this.colorDropdownIndex = this.colorDropdownIndex === index ? null : index;
        },
        updateSectionColor(index, color) {
            this.sorting.sections[index].color = color;
            this.colorDropdownIndex = null;
        },
        setTokenFromPreset: function (index, file = "") {

            if (file !== "") this.sorting.tokens[index].file = file;
            if (!this.fetching && this.sorting.tokens[index].file !== '') {
                let url = this.sorting.tokens[index].file;
                this.image = '';
                window.axios({
                    method: 'get', url: url, responseType: 'arraybuffer', headers: {
                        'Content-type': 'image/*',
                    },
                }).then(response => {

                    let image = btoa(new Uint8Array(response.data).reduce((data, byte) => data + String.fromCharCode(byte), ''));

                    let base64 = `data:${response.headers['content-type'].toLowerCase()};base64,${image}`;
                    let im = new Image();
                    im.src = base64;
                    let self = this;
                    im.onload = function () {
                        let canvas = document.createElement('canvas'), ctx = canvas.getContext('2d');
                        canvas.width = 100;
                        canvas.height = 100;
                        if (im.width < 100 || im.height < 100) {
                            ctx.drawImage(im, 0, 0, 100, 100, 0, 0, canvas.width, canvas.height);
                        } else {
                            self.drawImageProp(ctx, im, 0, 0, canvas.width, canvas.height);
                        }

                        let newimgUri = canvas.toDataURL('image/*').toString();

                        document.getElementById('token' + index).src = newimgUri;

                        self.sorting.tokens[index].base64 = newimgUri;
                        self.sorting.tokens[index].ispreset = true;

                    };
                });
                if (this.sorting.tokens[index].name == '') {
                    this.sorting.tokens[index].name = this.sorting.tokens[index].file.basename;
                }
            }
        },
    }
    ,
}
;
</script>
<style
    scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.custom-number-input input:focus {
    outline: none !important;
}

.custom-number-input button:focus {
    outline: none !important;
}

.file-input {
    display: none;
}
</style>
