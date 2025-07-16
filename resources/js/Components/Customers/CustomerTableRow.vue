<script setup lang="ts">
import type { Customer } from '@models/Customer';
import type { TableColumn } from '@/types/table';

interface Props {
    item: Customer;
    columns?: TableColumn[];
}

const props = withDefaults(defineProps<Props>(), {
    columns: () => []
});

// Check if a column is visible
const isColumnVisible = (columnKey: string): boolean => {
    if (!props.columns || props.columns.length === 0) return true;
    const column = props.columns.find(col => col.key === columnKey);
    return column ? column.visible !== false : false;
};

// Get column alignment class
const getColumnAlignClass = (columnKey: string): string => {
    const column = props.columns?.find(col => col.key === columnKey);
    switch (column?.align) {
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
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
        <td v-if="isColumnVisible('customer')" class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ item.name }}
                    </div>
                    <div v-if="item.reference" class="text-sm text-gray-500 dark:text-gray-400">
                        Ref: {{ item.reference }}
                    </div>
                </div>
            </div>
        </td>
        <td v-if="isColumnVisible('category')" :class="['px-6 py-4 whitespace-nowrap', getColumnAlignClass('category')]">
            <div v-if="item.customer_category" class="text-sm text-gray-900 dark:text-gray-100">
                {{ item.customer_category.name }}
            </div>
            <div v-else class="text-sm text-gray-500 dark:text-gray-400">
                No category
            </div>
        </td>
        <td v-if="isColumnVisible('started')" :class="['px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400', getColumnAlignClass('started')]">
            {{ item.started_at }}
        </td>
        <td v-if="isColumnVisible('actions')" :class="['px-6 py-4 whitespace-nowrap text-sm font-medium', getColumnAlignClass('actions')]">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </td>
    </tr>
</template>
