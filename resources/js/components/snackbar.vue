```vue
<template>
    <transition name="fade">
        <div v-if="visible" class="fixed bottom-0 left-0 right-0 mb-4 ml-4 max-w-sm p-4 bg-gray-800 text-white rounded shadow-lg z-50">
            <p>{{ message }}</p>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            required: true,
        },
        duration: {
            type: Number,
            default: 3000,
        },
    },
    data() {
        return {
            visible: false,
        };
    },
    methods: {
        show() {
            this.visible = true;
            setTimeout(() => {
                this.visible = false;
            }, this.duration);
        },
    },
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
