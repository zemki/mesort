<template>
  <div class="relative inline-block w-full">
    <button
      @click="toggleDropdown"
      type="button"
      class="inline-flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      <span>{{ selectedOption ? selectedOption.name : 'Select an option' }}</span>
      <svg
        class="w-5 h-5 text-gray-400"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
      >
        <path
          fill-rule="evenodd"
          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <div
      v-if="isOpen"
      class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
    >
      <ul
        tabindex="-1"
        role="listbox"
        aria-labelledby="listbox-label"
        aria-activedescendant="listbox-option-0"
        class="py-1 overflow-auto text-base leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:text-sm sm:leading-5"
      >
        <li
          v-for="(option, index) in options"
          :key="index"
          @click="selectOption(option)"
          class="relative py-2 pl-3 text-gray-900 cursor-pointer select-none pr-9 hover:bg-blue-100"
        >
          <div class="flex items-center">
            <span class="ml-3 block truncate">{{ option.name }}</span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    options: {
      type: Array,
      required: true,
    },
    value: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isOpen: false,
      selectedOption: this.value,
    };
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen;
    },
    selectOption(option) {
      this.selectedOption = option;
      this.isOpen = false;
      this.$emit('input', option);
    },
  },
  watch: {
    value(newValue) {
      this.selectedOption = newValue;
    },
  },
};
</script>
