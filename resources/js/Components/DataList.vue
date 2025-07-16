<script setup lang="ts">
import { ref, computed } from 'vue';
import type { TableColumn } from '@/types/table';

interface Props {
    title: string;
    subtitle?: string;
    items: any[];
    columns?: TableColumn[];
    loading?: boolean;
    searchPlaceholder?: string;
    showCreateButton?: boolean;
    createButtonText?: string;
    showPagination?: boolean;
    showHeaders?: boolean;
    emptyStateTitle?: string;
    emptyStateMessage?: string;
}

const props = withDefaults(defineProps<Props>(), {
    loading: false,
    searchPlaceholder: 'Search...',
    showCreateButton: true,
    createButtonText: 'Create New',
    showPagination: true,
    showHeaders: true,
    emptyStateTitle: 'No items found',
    emptyStateMessage: 'Get started by creating a new item.',
});

const emit = defineEmits<{
    search: [query: string];
    create: [];
    'item-click': [item: any];
}>();

const searchQuery = ref('');

// Filter visible columns
const visibleColumns = computed(() => {
    return props.columns?.filter(col => col.visible !== false) || [];
});

// Get column alignment class
const getColumnAlignClass = (align?: string) => {
    switch (align) {
        case 'center':
            return 'text-center';
        case 'right':
            return 'text-right';
        default:
            return 'text-left';
    }
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <!-- Header with title and search -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ title }}
                    </h3>
                    <p v-if="subtitle" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ subtitle }}
                    </p>
                </div>

                <!-- Search Section -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="searchPlaceholder"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            @input="$emit('search', searchQuery)"
                        />
                    </div>

                    <button
                        v-if="showCreateButton"
                        @click="$emit('create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ createButtonText }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-hidden">
            <div v-if="loading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <div v-else-if="items.length === 0" class="text-center py-8 px-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ emptyStateTitle }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ emptyStateMessage }}</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700" v-if="showHeaders">
                        <slot name="headers" :columns="visibleColumns">
                            <tr>
                                <th
                                    v-for="column in visibleColumns"
                                    :key="column.key"
                                    :class="[
                                        'px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider',
                                        getColumnAlignClass(column.align)
                                    ]"
                                    :style="{ width: column.width }"
                                >
                                    <div class="flex items-center space-x-1">
                                        <span>{{ column.label }}</span>
                                    </div>
                                </th>
                                <th v-if="!visibleColumns.length" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Item
                                </th>
                            </tr>
                        </slot>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <slot name="items" :items="items" :columns="visibleColumns">
                            <tr
                                v-for="item in items"
                                :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200"
                                @click="$emit('item-click', item)"
                            >
                                <slot name="item" :item="item" :columns="visibleColumns">
                                    <!-- Default item display -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ item.name || item.title || 'Item' }}
                                                </div>
                                                <div v-if="item.description" class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ item.description }}
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <slot name="item-actions" :item="item"></slot>
                                            </div>
                                        </div>
                                    </td>
                                </slot>
                            </tr>
                        </slot>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="showPagination && items.length > 0" class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
            <slot name="pagination">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing {{ items.length }} results
                    </div>
                </div>
            </slot>
        </div>
    </div>
</template>
