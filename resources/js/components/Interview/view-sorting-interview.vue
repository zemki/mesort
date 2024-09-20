<template>
    <div>
        <div class="block" v-show="actualData.length > 1">
            <nav
                class="relative z-0 flex divide-x divide-gray-200 rounded-lg shadow"
                aria-label="Tabs"
            >
                <a
                    v-for="(data, index) in actualData"
                    :key="index"
                    class="relative flex-1 min-w-0 px-4 py-4 overflow-hidden text-sm font-medium text-center text-gray-900 bg-white rounded-l-lg group hover:bg-gray-50 focus:z-10"
                    href="#"
                    :aria-current="openTab !== index ? '' : 'page'"
                    v-on:click.prevent="toggleTabs(index)"
                >
                    {{ index + 1 }}
                </a>
            </nav>
        </div>
        <div
            :class="{ hidden: openTab !== index, block: openTab === index }"
            v-for="(data, index) in actualData"
            :key="index"
        >
            <img
                :src="data.image"
                :alt="'screenshot' + index"
                class="w-full h-full"
            />
        </div>
    </div>
</template>
<script>
export default {
    props: ["screenshots"],

    data() {
        return {
            openTab: 0,
            actualData: this.screenshots,
        };
    },
    methods: {
        toggleTabs: function (tabNumber) {
            this.openTab = tabNumber;
        },
    },
};
</script>
