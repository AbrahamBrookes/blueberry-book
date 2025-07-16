<script setup lang="ts">

import type { Contact } from '@models/Contact';
import type { TableColumn } from '@/types/table';
import { formatISOStringDate } from '@/utils/dateHelpers';

const props = defineProps<{
    contact: Contact;
    columns?: TableColumn[];
}>();

const emit = defineEmits<{
    'edit-contact': [contact: Contact];
    'destroy-contact': [contact: Contact];
}>();

const deleteContact = () => {
    if (confirm(`Are you sure you want to delete ${props.contact.first_name} ${props.contact.last_name}?`)) {
        emit('destroy-contact', props.contact);
    }
};

</script>

<template>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            {{ contact.first_name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
            {{ contact.last_name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
            <button
                type="button"
                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3"
                @click="$emit('edit-contact', contact)"
            >
                Edit
            </button>
            <button
                type="button"
                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                @click="deleteContact"
            >
                Delete
            </button>
        </td>
    </tr>
</template>
