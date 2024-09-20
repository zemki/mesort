<!-- Navigation -->
<nav class="z-0 flex flex-wrap items-center justify-center w-full py-2 m-0 overflow-hidden bg-blue-500"
     role="navigation" aria-label="main navigation" id="main-nav-interview"
     style="-webkit-overflow-scrolling: touch; overscroll-behavior: none; touch-action:none;">
    <div class="relative left-0 flex items-center text-white shrink">
        <!-- Home button -->
        <div class="flex-shrink-0 mr-2">
            <a class="flex items-center justify-center text-white align-middle hover:text-gray-200 cursor-pointer"
               @click="confirmgohome" title="Home button">
                <img class="w-auto h-10" src="{{config('utilities.base64logo')}}" alt="MeSort Logo">
                <p class="px-3 py-2 text-sm font-medium text-gray-200 rounded-md hover:text-white">{{ __('Home') }}</p>
            </a>
        </div>

        <!-- Back button logic -->
        <button type="button" @click="interviewconfirmpreviouspage"
                v-if="interviewpage >= 1 && interviewpage != 2 && presortQuestions && sorting == 1"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            ←
            <span class="sr-only">{{__('Questions')}}</span>
            {{__('Questions')}}
        </button>

        <!-- If in post-sort questions, show "Back to Questions" -->
        <button type="button" @click="interviewconfirmpreviouspage"
                v-else-if="interviewpage == 2 && postsortQuestions"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            ←
            <span class="sr-only">{{__('Sorting')}}</span>
            {{__('Sorting')}}
        </button>

        <!-- Go Back to Sorting -->
        <button type="button" @click="interviewconfirmpreviouspage"
                v-else-if="interviewpage == 1 && sorting > 1"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            ←
            <span class="sr-only">{{__('Previous Sorting')}}</span>
            {{__('Previous Sorting')}}
        </button>

        <!-- Current page label -->
        <p class="mx-2 text-xl cursor-default hover:text-white" title="current page">
            @{{ interviewpagenames[interviewpage] }}
        </p>

        <!-- Next button logic -->
        <button @click="interviewconfirmnextpage" type="button"
                v-if="interviewpage == 1 && postsortQuestions && sorting == sortingtotal"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            {{__('Questions')}}
            <span class="sr-only">{{__('Go to Questions')}}</span>
            →
        </button>

        <button type="button" @click="interviewconfirmnextpage" v-else-if="interviewpage == 0"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{__('Sorting')}}</span>
            {{__('Sorting')}}
            →
        </button>

        <button type="button" @click="interviewconfirmnextpage" v-else-if="interviewpage > 0 && sorting < sortingtotal"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{__('Next Sorting')}}</span>
            {{__('Next Sorting')}}
            →
        </button>

        <button type="button" @click="interviewconfirmnextpage" v-else-if="interviewpage >= 1 && !postsortQuestions"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{__('End Interview')}}</span>
            {{__('End Interview')}}
            ✔️
        </button>

        <button type="button" @click="interviewconfirmnextpage" v-else-if="interviewpage > 1"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{__('End Interview')}}</span>
            {{__('End Interview')}}
            ✔️
        </button>
    </div>
    <div v-show="interviewpage == 1" class="flex items-center ml-2">
        <button type="button" @click="interviewaddsorting" v-if="sortingType !== 3"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{ __('Add Sorting') }}</span>
            {{ __('Add Sorting') }}
        </button>
        <button type="button" @click="interviewaddtoken" v-if="sortingType == 1"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{ __('Add Token') }}</span>
            {{ __('Add Token') }}
        </button>
        <button type="button" @click="togglePersonModal()" v-if="sortingType == 2"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{ __('Add Person') }}</span>
            {{ __('Add Person') }}
        </button>
        <button type="button" @click="toggleCardsWindow" v-if="sortingType == 3"
                class="relative inline-flex items-center px-2 py-2 ml-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <span class="sr-only">{{ __('Add Card') }}</span>
            {{ __('Add Card') }}
        </button>
        <div class="flex items-center ml-2 shrink" v-if="sortingType == 3">
            <img :src="selectedToken.image_path" class="w-12 h-12 z-100"
                 :class="Object.keys(selectedToken).length === 0 ? 'opacity-0':'opacity-100'">
            <button
                class="w-10 h-10 ml-2 text-white bg-gray-700 rounded-md cursor-pointer hover:text-gray-300 hover:bg-gray-900"
                @click.prevent="updateqsortSizeIndex('minus')">
                <span class="m-auto text-2xl font-bold">-</span>
            </button>
            <button
                class="w-10 h-10 ml-2 text-white bg-gray-700 rounded-md cursor-pointer hover:text-gray-300 hover:bg-gray-900"
                @click.prevent="updateqsortSizeIndex('plus')">
                <span class="m-auto text-2xl font-bold">+</span>
            </button>
            <button
                class="w-10 h-10 ml-2 text-white bg-gray-700 rounded-md cursor-pointer hover:text-gray-300 hover:bg-gray-900"
                @click.prevent="updateqsortDistanceIndex('plus')">
                ↓
            </button>
            <button
                class="w-10 h-10 ml-2 text-white bg-gray-700 rounded-md cursor-pointer hover:text-gray-300 hover:bg-gray-900"
                @click.prevent="updateqsortDistanceIndex('minus')">
                ↑
            </button>
        </div>
    </div>
</nav>
