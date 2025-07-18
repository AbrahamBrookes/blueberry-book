
<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue';

interface Props {
    message: string;
    type?: 'success' | 'error' | 'warning' | 'info';
    title?: string;
    duration?: number;
    show?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'info',
    duration: 5000,
    show: true,
});

const emit = defineEmits<{
    close: [];
}>();

const show = ref(props.show);

let timeoutId: number | null = null;

const bgColorClass = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-50 border-green-200 dark:bg-green-800 dark:border-green-700';
        case 'error':
            return 'bg-red-50 border-red-200 dark:bg-red-800 dark:border-red-700';
        case 'warning':
            return 'bg-yellow-50 border-yellow-200 dark:bg-yellow-800 dark:border-yellow-700';
        default:
            return 'bg-blue-50 border-blue-200 dark:bg-blue-800 dark:border-blue-700';
    }
});

const close = () => {
    show.value = false;
    if (timeoutId) {
        clearTimeout(timeoutId);
    }
    emit('close');
};

const startTimer = () => {
    if (props.duration > 0) {
        timeoutId = window.setTimeout(() => {
            close();
        }, props.duration);
    }
};

onMounted(() => {
    if (show.value) {
        startTimer();
    }
});

watch(() => props.show, (newShow) => {
    show.value = newShow;
    if (newShow) {
        startTimer();
    } else if (timeoutId) {
        clearTimeout(timeoutId);
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="opacity-100 translate-y-0 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed bottom-4 right-4 z-50 max-w-sm w-full border border-gray-200 rounded-lg shadow-lg dark:border-gray-700"
            :class="bgColorClass"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Success Icon -->
                        <svg
                            v-if="type === 'success'"
                            class="w-5 h-5 text-green-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>

                        <!-- Error Icon -->
                        <svg
                            v-else-if="type === 'error'"
                            class="w-5 h-5 text-red-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"
                            />
                        </svg>

                        <!-- Warning Icon -->
                        <svg
                            v-else-if="type === 'warning'"
                            class="w-5 h-5 text-yellow-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>

                        <!-- Info Icon (default) -->
                        <svg
                            v-else
                            class="w-5 h-5 text-blue-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>

                    <div class="ml-3 w-0 flex-1">
                        <p
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            v-if="title"
                        >
                            {{ title }}
                        </p>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400"
                            :class="{ 'mt-1': title }"
                        >
                            {{ message }}
                        </p>
                    </div>

                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="close"
                            class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-50"
                        >
                            <span class="sr-only">Close</span>
                            <svg
                                class="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
