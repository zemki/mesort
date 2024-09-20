<template>
    <div>
        <div class="fixed inset-0 bg-black opacity-50 z-40"></div>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
                <p class="mb-6" v-html="message"></p>
                <div class="flex justify-end">
                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
                            @click="onCancel">Cancel
                    </button>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            @click="onConfirm">{{ confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        message: {
            type: String,
            required: true
        },
        confirmText: {
            type: String,
            required: true
        },
        onConfirm: {
            type: Function,
            required: true
        },
        onCancel: {
            type: Function,
            required: true
        }
    }
};
</script>

<style scoped>
/* Add any additional styles for the custom dialog here */
</style>
