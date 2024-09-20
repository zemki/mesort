<template>
    <div :class="`fixed bottom-4 left-1/2 transform -translate-x-1/2 p-4 rounded shadow-lg ${toastClasses} ${fadeOut ? 'opacity-0' : 'opacity-100'}`" @click="closeToast">
        {{ message }}
    </div>
</template>

<script>
export default {
    props: {
        message: String,
        type: String,
        timeout: {
            type: Number,
            default: 1000
        }
    },
    data() {
        return {
            fadeOut: false,
        };
    },
    computed: {
        toastClasses() {
            switch (this.type) {
                case 'success':
                    return 'bg-green-500 text-white';
                case 'danger':
                    return 'bg-red-500 text-white';
                default:
                    return 'bg-gray-500 text-white';
            }
        },
    },
    mounted() {
        setTimeout(() => {
            this.fadeOut = true;
            setTimeout(() => {
                this.$emit('close');
            }, 500); // Duration of fade-out transition
        }, this.timeout);
    },
    methods: {
        closeToast() {
            this.fadeOut = true;
            setTimeout(() => {
                this.$emit('close');
            }, 500); // Duration of fade-out transition
        }
    }
};
</script>

<style scoped>
/* Add any additional styles for the custom toast here */
.fixed {
    transition: opacity 0.5s ease;
}
</style>
