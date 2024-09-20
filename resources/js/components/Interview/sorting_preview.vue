<template>
    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center z-50 preview-content" v-if="isModalVisible">
        <div class="absolute inset-0 bg-gray-900 opacity-50" @click="toggleSortingPreviewModal"></div>

        <div class="bg-white w-full max-w-3xl mx-auto rounded shadow-lg z-50 overflow-hidden">
            <div class="relative">
                <div class="absolute top-0 right-0 mt-4 mr-4 cursor-pointer text-white"
                     @click="toggleSortingPreviewModal">
                    <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                        ></path>
                    </svg>
                </div>
            </div>

            <div class="py-4 px-6">
                <!-- Title -->
                <div class="flex justify-between items-center pb-3 sorting-preview-title">
                    <p class="text-2xl font-bold">{{ trans('Sorting Preview') }}</p>
                    <div @click="toggleSortingPreviewModal" class="cursor-pointer">
                        <svg class="fill-current text-black w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            ></path>
                        </svg>
                    </div>
                </div>

                <!-- Body -->
                <div class="relative overflow-y-scroll" style="height: 80vh">
                    <div id="sortingsdiv" class="h-full flex items-center justify-center relative">
                        <div class="circle_container_preview is-unselectable"></div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end pt-2">
                    <button
                        class="px-4 py-2 bg-blue-500 rounded-lg text-white hover:bg-blue-400"
                        @click="toggleSortingPreviewModal"
                    >
                        {{ trans('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</template>

<script>
import sortingMixin from '../shared/sortingMixin';

export default {
    props: ['circles', 'sortingdetails', 'sectionsNumber', 'sortingid', 'sections', 'centerLabel'],
    mixins: [sortingMixin],
    data() {
        return {
            isModalVisible: false,
            bounds: {},
            center_x: 0,
            center_y: 0,
            sectionLabelColors: [],
            sectionsLabelColorsAreCasual: false,
            sectionNames: [],
        };
    },
    watch: {
        isModalVisible() {
            if (this.isModalVisible) {
                setTimeout(() => {
                    this.show();
                }, 200);
            }
        },
    },
    computed: {
        actualSections() {
            return JSON.parse(JSON.stringify(this.sections));
        },
    },
    methods: {
        toggleSortingPreviewModal() {
            this.isModalVisible = !this.isModalVisible;
        },
        show() {
            if (!this.isModalVisible) return;
            this.removePreviousElements();

            if (this.sortingid === 1 || this.sortingid === 2) {
                let mainContainer = document.querySelector('.circle_container_preview');
                mainContainer.innerHTML = ''; // Clear existing circles
                this.createCircles(this.circles, mainContainer);

                this.calculateCircleCenter();
            }

            if (this.sortingid === 2) {
                document.querySelectorAll('.triangle').forEach((el) => el.remove());
                this.updateSectionDetails();
                console.log("width", document.querySelector('.round-sorting' + this.circles).getBoundingClientRect().width)
                console.log("height", document.querySelector('.round-sorting' + this.circles).getBoundingClientRect().height)
                console.log("center_x", this.center_y)
                console.log("center_y", this.center_y)
                this.createSections(this.sections, this.circles, this.center_x, this.center_y);
            }
        },
        calculateCircleCenter() {
            let parentPos = document.querySelector('.circle_container_preview').getBoundingClientRect();
            let childPos = document.querySelector('.round-sorting' + this.circles).getBoundingClientRect();
            let title = document.querySelector('.sorting-preview-title').getBoundingClientRect();
            this.bounds.top = parentPos.top - childPos.top - title.height;
            this.bounds.right = childPos.right;
            this.bounds.bottom = childPos.bottom;
            this.bounds.left = parentPos.left - childPos.left;
            this.center_x = this.bounds.left - (document.querySelector('.round-sorting' + this.circles).offsetWidth) / 2;
            this.center_y = this.bounds.top + (document.querySelector('.round-sorting' + this.circles).offsetHeight) / 2;
        },
        updateSectionDetails() {
            let outputNames = this.actualSections.map((x) => x.name);
            let outputColors = this.actualSections.map((x) => x.color);

            if (outputColors.includes('')) {
                this.sectionsLabelColorsAreCasual = true;
                outputColors = ['#CC1F1A', '#DE751F', '#F2D024', '#1F9D55', '#38A89D', '#2779BD', '#5661B3', '#794ACF', '#EB5286'];
            }

            this.sectionsLabelColors = outputColors;
            this.sectionNames = outputNames;
        },
        removePreviousElements() {
            document.querySelectorAll('.round-sorting, .sectionDivider').forEach((el) => el.remove());
        },
    },
};
</script>

<style scoped>
#sortingsdiv {
    width: 100%;
    height: 100%;
}
</style>
